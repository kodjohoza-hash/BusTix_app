<?php
namespace App\Http\Controllers\Guichet;
use App\Http\Controllers\Controller;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with('bus')->latest()->paginate(10);
        return view('guichet.drivers', compact('drivers'));
    }
}