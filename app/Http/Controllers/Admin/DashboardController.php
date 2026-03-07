<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Ticket_reservation;

/**
 * DashboardController - Tableau de bord Admin (Web)
 * 
 * Récupère toutes les statistiques nécessaires
 * pour afficher le tableau de bord de BusTix.
 */
class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord principal
     */
    public function index()
    {
        return view('admin.dashboard', [
            // Statistiques des Bus
            'totalBuses'            => Bus::count(),

            // Statistiques des Clients
            'totalCustomers'        => Customer::count(),

            // Statistiques des Paiements
            'totalRevenue'          => Payment::sum('amount'),

            // Statistiques des Réservations
            'totalReservations'     => Ticket_reservation::count(),
            'pendingReservations'   => Ticket_reservation::where('status', 'en_attente')->count(),
            'confirmedReservations' => Ticket_reservation::where('status', 'confirmée')->count(),
            'cancelledReservations' => Ticket_reservation::where('status', 'annulée')->count(),

            // 5 dernières réservations
            'latestReservations'    => Ticket_reservation::with([
                                            'customer',
                                            'trip.displacement'
                                        ])->latest()->take(5)->get(),

            // 5 prochains voyages
            'upcomingTripsList'     => Trip::with(['displacement.bus'])
                                           ->where('travel_status', 'planifié')
                                           ->where('living_date_time', '>', now())
                                           ->orderBy('living_date_time')
                                           ->take(5)
                                           ->get(),
        ]);
    }
}
