<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Displacement;

class HomeController extends Controller
{
    /**
     * Page d'accueil client
     */
    public function index()
    {
        // 4 prochains voyages disponibles pour la home
        $featuredTrips = Trip::with(['displacement.bus'])
                             ->where('travel_status', 'planifié')
                             ->where('living_date_time', '>', now())
                             ->orderBy('living_date_time')
                             ->take(4)
                             ->get();

        return view('pages.home', compact('featuredTrips'));
    }
}