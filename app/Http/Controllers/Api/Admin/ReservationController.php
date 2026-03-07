<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket_reservation;
use Illuminate\Http\Request;

/**
 * ReservationController - Gestion des réservations (Admin)
 * 
 * Ce controller gère toutes les opérations sur les
 * réservations de billets de BusTix.
 * L'admin peut voir, confirmer et annuler les réservations.
 * Accessible uniquement par les administrateurs.
 */
class ReservationController extends Controller
{
    /**
     * Affiche la liste de toutes les réservations
     * GET /api/admin/reservations
     */
    public function index()
    {
        try {
            // Récupère toutes les réservations avec leurs relations
            $reservations = Ticket_reservation::with([
                'customer',
                'trip.displacement',
                'seat',
                'payment'
            ])->latest()->get();

            return response()->json([
                'success' => true,
                'message' => 'Liste des réservations récupérée avec succès',
                'data'    => $reservations
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des réservations',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée une nouvelle réservation (par l'admin)
     * POST /api/admin/reservations
     */
    public function store(Request $request)
    {
        try {
            // Validation des données envoyées
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'trip_id'     => 'required|exists:trips,id',
                'seat_id'     => 'required|exists:seats,id',
            ]);

            // Vérifie si le siège est déjà réservé pour ce voyage
            $seatTaken = Ticket_reservation::where('trip_id', $validated['trip_id'])
                                           ->where('seat_id', $validated['seat_id'])
                                           ->where('status', '!=', 'annulée')
                                           ->exists();

            if ($seatTaken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce siège est déjà réservé pour ce voyage'
                ], 409);
            }

            // Création de la réservation
            $reservation = Ticket_reservation::create([
                'customer_id'      => $validated['customer_id'],
                'trip_id'          => $validated['trip_id'],
                'seat_id'          => $validated['seat_id'],
                'reservation_date' => now(),
                'status'           => Ticket_reservation::STATUS_PENDING,
                // Génération automatique du code billet unique
                'ticket_code'      => Ticket_reservation::generateTicketCode(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réservation créée avec succès',
                'data'    => $reservation->load([
                    'customer',
                    'trip.displacement',
                    'seat'
                ])
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
                'message' => 'Erreur lors de la création de la réservation',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'une réservation spécifique
     * GET /api/admin/reservations/{id}
     */
    public function show(string $id)
    {
        try {
            // Récupère la réservation avec toutes ses relations
            $reservation = Ticket_reservation::with([
                'customer.user',
                'trip.displacement.bus',
                'seat',
                'payment'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Réservation récupérée avec succès',
                'data'    => $reservation
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Réservation non trouvée'
            ], 404);
        }
    }

    /**
     * Met à jour le statut d'une réservation
     * PUT /api/admin/reservations/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            // Récupère la réservation à modifier
            $reservation = Ticket_reservation::findOrFail($id);

            // Validation du statut
            $validated = $request->validate([
                'status' => 'required|in:en_attente,confirmée,annulée',
            ]);

            // Vérifie qu'on ne modifie pas une réservation déjà annulée
            if ($reservation->status === Ticket_reservation::STATUS_CANCELLED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de modifier une réservation annulée'
                ], 409);
            }

            // Mise à jour du statut
            $reservation->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Statut de la réservation mis à jour avec succès',
                'data'    => $reservation->load([
                    'customer',
                    'trip.displacement',
                    'seat',
                    'payment'
                ])
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
                'message' => 'Réservation non trouvée'
            ], 404);
        }
    }

    /**
     * Annule et supprime une réservation
     * DELETE /api/admin/reservations/{id}
     */
    public function destroy(string $id)
    {
        try {
            // Récupère la réservation à supprimer
            $reservation = Ticket_reservation::findOrFail($id);

            // Vérifie si la réservation a un paiement confirmé
            if ($reservation->payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer une réservation déjà payée'
                ], 409);
            }

            // Suppression de la réservation
            $reservation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Réservation supprimée avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Réservation non trouvée'
            ], 404);
        }
    }
}
