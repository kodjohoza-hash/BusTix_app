<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Ticket_reservation;
use App\Models\Displacement;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== STATISTIQUES PRINCIPALES =====
        $totalBuses            = Bus::count();
        $totalCustomers        = Customer::count();
        $totalRevenue          = Payment::sum('amount');
        $totalReservations     = Ticket_reservation::count();
        $pendingReservations   = Ticket_reservation::where('status', 'en_attente')->count();
        $confirmedReservations = Ticket_reservation::where('status', 'confirmée')->count();
        $cancelledReservations = Ticket_reservation::where('status', 'annulée')->count();

        // ===== 5 DERNIÈRES RÉSERVATIONS =====
        $latestReservations = Ticket_reservation::with([
                                'customer',
                                'trip.displacement'
                            ])->latest()->take(5)->get();

        // ===== 5 PROCHAINS VOYAGES =====
        $upcomingTripsList = Trip::with(['displacement.bus'])
                                 ->where('travel_status', 'planifié')
                                 ->where('living_date_time', '>', now())
                                 ->orderBy('living_date_time')
                                 ->take(5)
                                 ->get();

        // ===== DONNÉES GRAPHIQUES =====

        // 1. Bar Chart - Réservations des 6 derniers mois
        $monthLabels          = [];
        $reservationsPerMonth = [];
        $revenusPerMonth      = [];

        for ($i = 5; $i >= 0; $i--) {
            $month           = now()->subMonths($i);
            $monthLabels[]   = $month->format('M Y');

            $reservationsPerMonth[] = Ticket_reservation::whereYear('created_at', $month->year)
                                                        ->whereMonth('created_at', $month->month)
                                                        ->count();

            $revenusPerMonth[] = Payment::whereYear('created_at', $month->year)
                                        ->whereMonth('created_at', $month->month)
                                        ->sum('amount');
        }

        // 2. Pie Chart - Réservations par statut
        $statusData = [
            $pendingReservations,
            $confirmedReservations,
            $cancelledReservations,
        ];

        // 3. Pie Chart - Voyages par trajet
        $displacements      = Displacement::withCount('trips')->get();
        $displacementLabels = $displacements->map(fn($d) => $d->start_point.' → '.$d->destination_point)->toArray();
        $displacementData   = $displacements->pluck('trips_count')->toArray();

        return view('admin.dashboard', compact(
            'totalBuses',
            'totalCustomers',
            'totalRevenue',
            'totalReservations',
            'pendingReservations',
            'confirmedReservations',
            'cancelledReservations',
            'latestReservations',
            'upcomingTripsList',
            'monthLabels',
            'reservationsPerMonth',
            'revenusPerMonth',
            'statusData',
            'displacementLabels',
            'displacementData'
        ));
    }
}