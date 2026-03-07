<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Bus;
use Illuminate\Http\Request;

/**
 * SeatController - Gestion des sièges (Admin)
 * 
 * Ce controller gère toutes les opérations CRUD
 * sur les sièges des bus de BusTix.
 * Les sièges sont généralement créés automatiquement
 * lors de la création d'un bus, mais l'admin peut
 * les gérer manuellement ici.
 * Accessible uniquement par les administrateurs.
 */
class SeatController extends Controller
{
    /**
     * Affiche la liste de tous les sièges
     * GET /api/admin/seats
     */
    public function index()
    {
        try {
            // Récupère tous les sièges avec leur bus associé
            $seats = Seat::with('bus')->get();

            return response()->json([
                'success' => true,
                'message' => 'Liste des sièges récupérée avec succès',
                'data'    => $seats
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sièges',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée un nouveau siège manuellement
     * POST /api/admin/seats
     */
    public function store(Request $request)
    {
        try {
            // Validation des données envoyées
            $validated = $request->validate([
                'bus_id'      => 'required|exists:buses,id',
                'seat_number' => 'required|string|max:10',
            ]);

            // Vérifie que le numéro de siège n'existe pas déjà dans ce bus
            $seatExists = Seat::where('bus_id', $validated['bus_id'])
                              ->where('seat_number', $validated['seat_number'])
                              ->exists();

            if ($seatExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce numéro de siège existe déjà dans ce bus'
                ], 409);
            }

            // Création du siège
            $seat = Seat::create($validated);

            // Met à jour la capacité du bus
            $bus = Bus::findOrFail($validated['bus_id']);
            $bus->update([
                'capacity' => (string) ($bus->seats()->count())
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Siège créé avec succès',
                'data'    => $seat->load('bus')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du siège',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un siège spécifique
     * GET /api/admin/seats/{id}
     */
    public function show(string $id)
    {
        try {
            // Récupère le siège avec son bus et sa réservation active
            $seat = Seat::with([
                'bus',
                'ticketReservation.customer',
                'ticketReservation.trip'
            ])->findOrFail($id);

            // Vérifie si le siège est actuellement occupé
            $isOccupied = $seat->ticketReservation &&
                          $seat->ticketReservation->status !== 'annulée';

            return response()->json([
                'success' => true,
                'message' => 'Siège récupéré avec succès',
                'data'    => [
                    'seat'        => $seat,
                    'is_occupied' => $isOccupied,
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Siège non trouvé'
            ], 404);
        }
    }

    /**
     * Met à jour les informations d'un siège
     * PUT /api/admin/seats/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            // Récupère le siège à modifier
            $seat = Seat::findOrFail($id);

            // Validation des données
            $validated = $request->validate([
                'seat_number' => 'sometimes|string|max:10',
            ]);

            // Vérifie que le nouveau numéro n'existe pas déjà dans ce bus
            if (isset($validated['seat_number'])) {
                $seatExists = Seat::where('bus_id', $seat->bus_id)
                                  ->where('seat_number', $validated['seat_number'])
                                  ->where('id', '!=', $id)
                                  ->exists();

                if ($seatExists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce numéro de siège existe déjà dans ce bus'
                    ], 409);
                }
            }

            // Mise à jour du siège
            $seat->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Siège mis à jour avec succès',
                'data'    => $seat->load('bus')
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Siège non trouvé'
            ], 404);
        }
    }

    /**
     * Supprime un siège
     * DELETE /api/admin/seats/{id}
     */
    public function destroy(string $id)
    {
        try {
            // Récupère le siège à supprimer
            $seat = Seat::findOrFail($id);

            // Vérifie si le siège est actuellement réservé
            $isReserved = $seat->ticketReservation &&
                          $seat->ticketReservation->status !== 'annulée';

            if ($isReserved) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer un siège actuellement réservé'
                ], 409);
            }

            // Récupère le bus avant suppression
            $bus = $seat->bus;

            // Suppression du siège
            $seat->delete();

            // Met à jour la capacité du bus
            $bus->update([
                'capacity' => (string) ($bus->seats()->count())
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Siège supprimé avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Siège non trouvé'
            ], 404);
        }
    }
}
