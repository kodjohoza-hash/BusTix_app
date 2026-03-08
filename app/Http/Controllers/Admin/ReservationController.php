<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket_reservation;
use App\Models\Customer;
use App\Models\Trip;
use App\Models\Seat;

/**
 * ReservationController Web - Gestion des réservations (Admin)
 */
class ReservationController extends Controller
{
    /**
     * Affiche la liste de toutes les réservations
     */
    public function index()
    {
        $reservations = Ticket_reservation::with([
                            'customer',
                            'trip.displacement',
                            'seat',
                            'payment'
                        ])
                        ->latest()
                        ->paginate(15);

        // Statistiques
        $totalReservations     = Ticket_reservation::count();
        $pendingReservations   = Ticket_reservation::where('status', 'en_attente')->count();
        $confirmedReservations = Ticket_reservation::where('status', 'confirmée')->count();
        $cancelledReservations = Ticket_reservation::where('status', 'annulée')->count();

        return view('admin.reservations', compact(
            'reservations',
            'totalReservations',
            'pendingReservations',
            'confirmedReservations',
            'cancelledReservations'
        ));
    }

    /**
     * Met à jour le statut d'une réservation
     */
    public function updateStatus(\Illuminate\Http\Request $request, string $id)
    {
        $reservation = Ticket_reservation::findOrFail($id);

        $request->validate([
            'status' => 'required|in:en_attente,confirmée,annulée'
        ]);

        $reservation->update(['status' => $request->status]);

        return redirect()->route('admin.reservations')
                         ->with('success', 'Statut de la réservation mis à jour !');
    }

    /**
     * Supprime une réservation
     */
    public function destroy(string $id)
    {
        $reservation = Ticket_reservation::findOrFail($id);

        // Vérifie si la réservation a un paiement
        if ($reservation->payment) {
            return redirect()->route('admin.reservations')
                             ->with('error', 'Impossible de supprimer une réservation avec un paiement associé !');
        }

        $reservation->delete();

        return redirect()->route('admin.reservations')
                         ->with('success', 'Réservation supprimée avec succès !');
    }
}