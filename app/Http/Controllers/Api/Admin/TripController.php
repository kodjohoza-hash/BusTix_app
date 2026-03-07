<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Displacement;
use Illuminate\Http\Request;

/**
 * TripController - Gestion des voyages (Admin)
 * 
 * Ce controller gère toutes les opérations CRUD
 * sur les voyages planifiés de BusTix.
 * Un voyage est une instance d'un trajet à une date précise.
 * Accessible uniquement par les administrateurs.
 */
class TripController extends Controller
{
    /**
     * Affiche la liste de tous les voyages
     * GET /api/admin/trips
     */
    public function index()
    {
        try {
            // Récupère tous les voyages avec le trajet et le bus associés
            $trips = Trip::with(['displacement.bus'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Liste des voyages récupérée avec succès',
                'data'    => $trips
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des voyages',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée un nouveau voyage
     * POST /api/admin/trips
     */
    public function store(Request $request)
    {
        try {
            // Validation des données envoyées
            $validated = $request->validate([
                'displacement_id'  => 'required|exists:displacements,id',
                'living_date_time' => 'required|date|after:now',
                'price'            => 'required|numeric|min:0',
                'travel_status'    => 'required|in:planifié,en cours,terminé,annulé',
            ]);

            // Création du voyage en base de données
            $trip = Trip::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Voyage créé avec succès',
                'data'    => $trip->load('displacement.bus')
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
                'message' => 'Erreur lors de la création du voyage',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un voyage spécifique
     * GET /api/admin/trips/{id}
     */
    public function show(string $id)
    {
        try {
            // Récupère le voyage avec toutes ses relations
            $trip = Trip::with([
                'displacement.bus',
                'ticketReservations.customer',
                'ticketReservations.seat'
            ])->findOrFail($id);

            // Calcule le nombre de sièges disponibles
            $totalSeats     = $trip->displacement->bus->capacity;
            $reservedSeats  = $trip->ticketReservations()
                                   ->where('status', '!=', 'annulée')
                                   ->count();
            $availableSeats = $totalSeats - $reservedSeats;

            return response()->json([
                'success' => true,
                'message' => 'Voyage récupéré avec succès',
                'data'    => [
                    'trip'            => $trip,
                    'total_seats'     => $totalSeats,
                    'reserved_seats'  => $reservedSeats,
                    'available_seats' => $availableSeats,
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Voyage non trouvé'
            ], 404);
        }
    }

    /**
     * Met à jour les informations d'un voyage
     * PUT /api/admin/trips/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            // Récupère le voyage à modifier
            $trip = Trip::findOrFail($id);

            // Validation des données
            $validated = $request->validate([
                'displacement_id'  => 'sometimes|exists:displacements,id',
                'living_date_time' => 'sometimes|date',
                'price'            => 'sometimes|numeric|min:0',
                'travel_status'    => 'sometimes|in:planifié,en cours,terminé,annulé',
            ]);

            // Mise à jour du voyage
            $trip->update($validated);

            // Si le voyage est annulé, on annule toutes les réservations
            if (isset($validated['travel_status']) && 
                $validated['travel_status'] === 'annulé') {
                $trip->ticketReservations()
                     ->where('status', '!=', 'annulée')
                     ->update(['status' => 'annulée']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Voyage mis à jour avec succès',
                'data'    => $trip->load('displacement.bus')
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
                'message' => 'Voyage non trouvé'
            ], 404);
        }
    }

    /**
     * Supprime un voyage
     * DELETE /api/admin/trips/{id}
     */
    public function destroy(string $id)
    {
        try {
            // Récupère le voyage à supprimer
            $trip = Trip::findOrFail($id);

            // Vérifie si le voyage a des réservations confirmées
            $confirmedReservations = $trip->ticketReservations()
                                          ->where('status', 'confirmée')
                                          ->count();

            if ($confirmedReservations > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer ce voyage car il a ' 
                                 . $confirmedReservations 
                                 . ' réservation(s) confirmée(s)'
                ], 409);
            }

            // Suppression du voyage
            $trip->delete();

            return response()->json([
                'success' => true,
                'message' => 'Voyage supprimé avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Voyage non trouvé'
            ], 404);
        }
    }
}
