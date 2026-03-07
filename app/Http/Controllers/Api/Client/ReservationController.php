<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket_reservation;
use App\Models\Trip;
use Illuminate\Http\Request;

/**
 * ReservationController - Gestion des réservations (Client)
 * 
 * Ce controller permet aux clients de gérer
 * leurs propres réservations dans BusTix.
 * Un client ne peut voir et gérer que ses propres réservations.
 */
class ReservationController extends Controller
{
    /**
     * Affiche toutes les réservations du client connecté
     * GET /api/client/reservations
     */
    public function index()
    {
        try {
            // Récupère uniquement les réservations du client connecté
            $reservations = Ticket_reservation::with([
                'trip.displacement.bus',
                'seat',
                'payment'
            ])
            ->where('customer_id', auth()->user()->customer->id)
            ->latest()
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Vos réservations récupérées avec succès',
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
     * Crée une nouvelle réservation pour le client connecté
     * POST /api/client/reservations
     */
    public function store(Request $request)
    {
        try {
            // Validation des données envoyées
            $validated = $request->validate([
                'trip_id' => 'required|exists:trips,id',
                'seat_id' => 'required|exists:seats,id',
            ]);

            // Récupère le voyage choisi
            $trip = Trip::findOrFail($validated['trip_id']);

            // Vérifie que le voyage est encore disponible
            if ($trip->travel_status !== 'planifié') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce voyage n\'est plus disponible'
                ], 409);
            }

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

            // Vérifie que le client n'a pas déjà réservé ce voyage
            $alreadyBooked = Ticket_reservation::where('trip_id', $validated['trip_id'])
                                               ->where('customer_id', auth()->user()->customer->id)
                                               ->where('status', '!=', 'annulée')
                                               ->exists();

            if ($alreadyBooked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà une réservation pour ce voyage'
                ], 409);
            }

            // Création de la réservation
            $reservation = Ticket_reservation::create([
                'customer_id'      => auth()->user()->customer->id,
                'trip_id'          => $validated['trip_id'],
                'seat_id'          => $validated['seat_id'],
                'reservation_date' => now(),
                'status'           => Ticket_reservation::STATUS_PENDING,
                // Génération automatique du code billet unique
                'ticket_code'      => Ticket_reservation::generateTicketCode(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réservation effectuée avec succès',
                'data'    => $reservation->load([
                    'trip.displacement.bus',
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
                'message' => 'Erreur lors de la réservation',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'une réservation du client connecté
     * GET /api/client/reservations/{id}
     */
    public function show(string $id)
    {
        try {
            // Récupère la réservation uniquement si elle appartient au client connecté
            $reservation = Ticket_reservation::with([
                'trip.displacement.bus',
                'seat',
                'payment'
            ])
            ->where('customer_id', auth()->user()->customer->id)
            ->findOrFail($id);

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
     * Annule une réservation du client connecté
     * PUT /api/client/reservations/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            // Récupère la réservation uniquement si elle appartient au client connecté
            $reservation = Ticket_reservation::where('customer_id', auth()->user()->customer->id)
                                             ->findOrFail($id);

            // Vérifie que la réservation n'est pas déjà annulée
            if ($reservation->status === Ticket_reservation::STATUS_CANCELLED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette réservation est déjà annulée'
                ], 409);
            }

            // Vérifie que la réservation n'est pas déjà payée
            if ($reservation->payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible d\'annuler une réservation déjà payée'
                ], 409);
            }

            // Annulation de la réservation
            $reservation->update([
                'status' => Ticket_reservation::STATUS_CANCELLED
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réservation annulée avec succès',
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
     * Supprime une réservation annulée du client connecté
     * DELETE /api/client/reservations/{id}
     */
    public function destroy(string $id)
    {
        try {
            // Récupère la réservation uniquement si elle appartient au client connecté
            $reservation = Ticket_reservation::where('customer_id', auth()->user()->customer->id)
                                             ->findOrFail($id);

            // Seules les réservations annulées peuvent être supprimées
            if ($reservation->status !== Ticket_reservation::STATUS_CANCELLED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez supprimer que les réservations annulées'
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
