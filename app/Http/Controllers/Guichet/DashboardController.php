<?php

namespace App\Http\Controllers\Guichet;

use App\Http\Controllers\Controller;
use App\Models\Ticket_reservation;
use App\Models\Trip;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        // Voyages du jour
        $todayTrips = Trip::with(['displacement.bus'])
                          ->whereDate('living_date_time', today())
                          ->orderBy('living_date_time')
                          ->get();

        // Réservations du jour
        $todayReservations = Ticket_reservation::with(['trip.displacement', 'seat', 'customer'])
                                               ->whereDate('reservation_date', today())
                                               ->latest()
                                               ->get();

        // Stats du jour
        $todayRevenue    = Payment::whereDate('payment_date', today())->sum('amount');
        $todayConfirmed  = Ticket_reservation::whereDate('reservation_date', today())
                                             ->where('status', 'confirmée')->count();
        $todayPending    = Ticket_reservation::whereDate('reservation_date', today())
                                             ->where('status', 'en_attente')->count();
        $totalTodayTrips = $todayTrips->count();

        return view('guichet.dashboard', compact(
            'todayTrips',
            'todayReservations',
            'todayRevenue',
            'todayConfirmed',
            'todayPending',
            'totalTodayTrips'
        ));
    }
}