<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Displacement;
use App\Models\Bus;
use Illuminate\Http\Request;

/**
 * VoyageController Web - Gestion des voyages (Admin)
 */
class VoyageController extends Controller
{
    /**
     * Affiche la liste de tous les voyages
     */
    public function index()
    {
        $trips = Trip::with(['displacement.bus'])
                     ->orderBy('living_date_time', 'desc')
                     ->paginate(15);

        $displacements = Displacement::with('bus')->get();

        // Statistiques
        $totalTrips     = Trip::count();
        $plannedTrips   = Trip::where('travel_status', 'planifié')->count();
        $completedTrips = Trip::where('travel_status', 'terminé')->count();
        $cancelledTrips = Trip::where('travel_status', 'annulé')->count();

        return view('admin.voyages', compact(
            'trips',
            'displacements',
            'totalTrips',
            'plannedTrips',
            'completedTrips',
            'cancelledTrips'
        ));
    }

    /**
     * Crée un nouveau voyage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'displacement_id'  => 'required|exists:displacements,id',
            'living_date_time' => 'required|date|after:now',
            'price'            => 'required|numeric|min:0',
            'travel_status'    => 'required|in:planifié,en cours,terminé,annulé',
        ]);

        Trip::create($validated);

        return redirect()->route('admin.voyages')
                         ->with('success', 'Voyage créé avec succès !');
    }

    /**
     * Met à jour un voyage
     */
    public function update(Request $request, string $id)
    {
        $trip = Trip::findOrFail($id);

        $validated = $request->validate([
            'displacement_id'  => 'required|exists:displacements,id',
            'living_date_time' => 'required|date',
            'price'            => 'required|numeric|min:0',
            'travel_status'    => 'required|in:planifié,en cours,terminé,annulé',
        ]);

        $trip->update($validated);

        return redirect()->route('admin.voyages')
                         ->with('success', 'Voyage mis à jour avec succès !');
    }

    /**
     * Supprime un voyage
     */
    public function destroy(string $id)
    {
        $trip = Trip::findOrFail($id);

        // Vérifie si le voyage a des réservations
        if ($trip->ticket_reservations()->count() > 0) {
            return redirect()->route('admin.voyages')
                             ->with('error', 'Impossible de supprimer ce voyage car il a des réservations !');
        }

        $trip->delete();

        return redirect()->route('admin.voyages')
                         ->with('success', 'Voyage supprimé avec succès !');
    }
}