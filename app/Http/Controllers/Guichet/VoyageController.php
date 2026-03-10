<?php

namespace App\Http\Controllers\Guichet;

use App\Http\Controllers\Controller;
use App\Models\Trip;

class VoyageController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['displacement.bus'])
                     ->orderBy('living_date_time', 'desc')
                     ->paginate(15);

        return view('guichet.voyages', compact('trips'));
    }
}