<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Ticket_reservation;
use Illuminate\Http\Request;

/**
 * PaymentController - Gestion des paiements (Admin)
 * 
 * Ce controller gère toutes les opérations sur les
 * paiements effectués dans BusTix.
 * L'admin peut voir et gérer tous les paiements.
 * Accessible uniquement par les administrateurs.
 */
class PaymentController extends Controller
{
    /**
     * Affiche la liste de tous les paiements
     * GET /api/admin/payments
     */
    public function index()
    {
        try {
            // Récupère tous les paiements avec leurs relations
            $payments = Payment::with([
                'ticketReservation.customer',
                'ticketReservation.trip.displacement',
            ])->latest()->get();

            // Calcule le total des paiements
            $totalAmount = $payments->sum('amount');

            return response()->json([
                'success'      => true,
                'message'      => 'Liste des paiements récupérée avec succès',
                'total_amount' => $totalAmount,
                'data'         => $payments
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des paiements',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enregistre un nouveau paiement
     * POST /api/admin/payments
     */
    public function store(Request $request)
    {
        try {
            // Validation des données envoyées
            $validated = $request->validate([
                'reservation_id' => 'required|exists:ticket_reservations,id',
                'amount'         => 'required|numeric|min:0',
                'payment_mode'   => 'required|in:espèces,mobile_money,carte_bancaire',
                'payment_date'   => 'required|date',
            ]);

            // Récupère la réservation liée au paiement
            $reservation = Ticket_reservation::findOrFail($validated['reservation_id']);

            // Vérifie si la réservation n'est pas annulée
            if ($reservation->status === Ticket_reservation::STATUS_CANCELLED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible d\'enregistrer un paiement pour une réservation annulée'
                ], 409);
            }

            // Vérifie si la réservation n'est pas déjà payée
            if ($reservation->payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette réservation a déjà été payée'
                ], 409);
            }

            // Création du paiement avec référence unique
            $payment = Payment::create([
                'reservation_id'        => $validated['reservation_id'],
                'amount'                => $validated['amount'],
                'payment_mode'          => $validated['payment_mode'],
                'payment_date'          => $validated['payment_date'],
                // Génération automatique de la référence de transaction
                'transaction_reference' => Payment::generateTransactionReference(),
            ]);

            // Confirmation automatique de la réservation après paiement
            $reservation->update([
                'status' => Ticket_reservation::STATUS_CONFIRMED
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paiement enregistré avec succès',
                'data'    => $payment->load([
                    'ticketReservation.customer',
                    'ticketReservation.trip.displacement',
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
                'message' => 'Erreur lors de l\'enregistrement du paiement',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un paiement spécifique
     * GET /api/admin/payments/{id}
     */
    public function show(string $id)
    {
        try {
            // Récupère le paiement avec toutes ses relations
            $payment = Payment::with([
                'ticketReservation.customer.user',
                'ticketReservation.trip.displacement.bus',
                'ticketReservation.seat',
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Paiement récupéré avec succès',
                'data'    => $payment
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé'
            ], 404);
        }
    }

    /**
     * Met à jour un paiement
     * PUT /api/admin/payments/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            // Récupère le paiement à modifier
            $payment = Payment::findOrFail($id);

            // Validation des données
            $validated = $request->validate([
                'amount'       => 'sometimes|numeric|min:0',
                'payment_mode' => 'sometimes|in:espèces,mobile_money,carte_bancaire',
                'payment_date' => 'sometimes|date',
            ]);

            // Mise à jour du paiement
            $payment->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Paiement mis à jour avec succès',
                'data'    => $payment->load([
                    'ticketReservation.customer',
                    'ticketReservation.trip.displacement',
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
                'message' => 'Paiement non trouvé'
            ], 404);
        }
    }

    /**
     * Supprime un paiement
     * DELETE /api/admin/payments/{id}
     */
    public function destroy(string $id)
    {
        try {
            // Récupère le paiement à supprimer
            $payment = Payment::findOrFail($id);

            // Remet la réservation en attente après suppression du paiement
            $payment->ticketReservation->update([
                'status' => Ticket_reservation::STATUS_PENDING
            ]);

            // Suppression du paiement
            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Paiement supprimé avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé'
            ], 404);
        }
    }
}
