<?php
namespace App\Http\Controllers;

use App\Models\Trip;

class HomeController extends Controller
{
    // Pas de middleware ici - page publique !

    public function index()
    {
        $featuredTrips = Trip::with(['displacement.bus'])
                             ->where('travel_status', 'planifié')
                             ->where('living_date_time', '>', now())
                             ->orderBy('living_date_time')
                             ->take(4)
                             ->get();

        return view('pages.home', compact('featuredTrips'));
    }
}