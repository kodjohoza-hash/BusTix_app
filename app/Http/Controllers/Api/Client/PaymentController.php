<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Ticket_reservation;
use Illuminate\Http\Request;

/**
 * PaymentController - Gestion des paiements (Client)
 * 
 * Ce controller permet aux clients d'effectuer
 * et consulter leurs paiements dans BusTix.
 * Un client ne peut voir que ses propres paiements.
 */
class PaymentController extends Controller
{
    /**
     * Affiche tous les paiements du client connecté
     * GET /api/client/payments
     */
    public function index()
    {
        try {
            // Récupère uniquement les paiements du client connecté
            $payments = Payment::with([
                'ticketReservation.trip.displacement.bus',
                'ticketReservation.seat',
            ])
            ->whereHas('ticketReservation', function ($query) {
                $query->where('customer_id', auth()->user()->customer->id);
            })
            ->latest()
            ->get();

            // Calcule le total des paiements du client
            $totalAmount = $payments->sum('amount');

            return response()->json([
                'success'      => true,
                'message'      => 'Vos paiements récupérés avec succès',
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
     * Effectue un paiement pour une réservation
     * POST /api/client/payments
     */
    public function store(Request $request)
    {
        try {
            // Validation des données envoyées
            $validated = $request->validate([
                'reservation_id' => 'required|exists:ticket_reservations,id',
                'payment_mode'   => 'required|in:espèces,mobile_money,carte_bancaire',
            ]);

            // Récupère la réservation et vérifie qu'elle appartient au client connecté
            $reservation = Ticket_reservation::where('customer_id', auth()->user()->customer->id)
                                             ->findOrFail($validated['reservation_id']);

            // Vérifie que la réservation n'est pas annulée
            if ($reservation->status === Ticket_reservation::STATUS_CANCELLED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de payer une réservation annulée'
                ], 409);
            }

            // Vérifie que la réservation n'est pas déjà payée
            if ($reservation->payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette réservation a déjà été payée'
                ], 409);
            }

            // Récupère le prix du voyage comme montant à payer
            $amount = $reservation->trip->price;

            // Création du paiement
            $payment = Payment::create([
                'reservation_id'        => $reservation->id,
                'amount'                => $amount,
                'payment_mode'          => $validated['payment_mode'],
                'payment_date'          => now(),
                // Génération automatique de la référence de transaction
                'transaction_reference' => Payment::generateTransactionReference(),
            ]);

            // Confirmation automatique de la réservation après paiement
            $reservation->update([
                'status' => Ticket_reservation::STATUS_CONFIRMED
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paiement effectué avec succès ! Votre billet est confirmé.',
                'data'    => [
                    'payment'     => $payment->load([
                        'ticketReservation.trip.displacement.bus',
                        'ticketReservation.seat',
                    ]),
                    'ticket_code' => $reservation->ticket_code,
                    'amount'      => $amount,
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Réservation non trouvée ou ne vous appartient pas'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du paiement',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un paiement du client connecté
     * GET /api/client/payments/{id}
     */
    public function show(string $id)
    {
        try {
            // Récupère le paiement uniquement s'il appartient au client connecté
            $payment = Payment::with([
                'ticketReservation.trip.displacement.bus',
                'ticketReservation.seat',
            ])
            ->whereHas('ticketReservation', function ($query) {
                $query->where('customer_id', auth()->user()->customer->id);
            })
            ->findOrFail($id);

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
     * Un client ne peut pas modifier un paiement
     * PUT /api/client/payments/{id}
     */
    public function update(Request $request, string $id)
    {
        // Un paiement effectué ne peut pas être modifié par le client
        return response()->json([
            'success' => false,
            'message' => 'Vous n\'êtes pas autorisé à modifier un paiement'
        ], 403);
    }

    /**
     * Un client ne peut pas supprimer un paiement
     * DELETE /api/client/payments/{id}
     */
    public function destroy(string $id)
    {
        // Un paiement effectué ne peut pas être supprimé par le client
        return response()->json([
            'success' => false,
            'message' => 'Vous n\'êtes pas autorisé à supprimer un paiement'
        ], 403);
    }
}
