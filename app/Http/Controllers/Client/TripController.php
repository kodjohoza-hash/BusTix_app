<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Liste tous les voyages disponibles
     */
    public function index()
    {
        $trips = Trip::with(['displacement.bus'])
                     ->where('travel_status', 'planifié')
                     ->where('living_date_time', '>', now())
                     ->orderBy('living_date_time')
                     ->paginate(9);

        return view('pages.voyages', compact('trips'));
    }

    /**
     * Recherche des voyages
     */
    public function search(Request $request)
    {
        $departure   = $request->departure;
        $destination = $request->destination;

        $trips = Trip::with(['displacement.bus'])
                     ->where('travel_status', 'planifié')
                     ->where('living_date_time', '>', now())
                     ->whereHas('displacement', function($q) use ($departure, $destination) {
                         if ($departure) {
                             $q->where('start_point', 'like', '%'.$departure.'%');
                         }
                         if ($destination) {
                             $q->where('destination_point', 'like', '%'.$destination.'%');
                         }
                     })
                     ->orderBy('living_date_time')
                     ->paginate(9);

        return view('pages.search', compact('trips', 'departure', 'destination'));
    }

    /**
     * Détail d'un voyage avec sièges
     */
    public function show(string $id)
    {
        $trip = Trip::with([
                    'displacement.bus.seats',
                    'ticketReservations.seat'
                ])->findOrFail($id);

        // Sièges réservés
        $reservedSeats = $trip->ticketReservations()
                              ->where('status', '!=', 'annulée')
                              ->pluck('seat_id')
                              ->toArray();

        return view('pages.details', compact('trip', 'reservedSeats'));
    }
}