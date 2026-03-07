<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

/**
 * BusController Web - Gestion des bus (Admin)
 * 
 * Ce controller gère l'affichage et les opérations
 * CRUD sur les bus via les vues Blade.
 */
class BusController extends Controller
{
    /**
     * Affiche la liste de tous les bus
     */
    public function index()
    {
        // Récupère tous les bus avec le nombre de sièges
        $buses = Bus::withCount('seats')->get();

        return view('admin.bus', compact('buses'));
    }

    /**
     * Crée un nouveau bus
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'bus_number' => 'required|string|max:100|unique:buses',
            'mack'       => 'required|string|max:30',
            'capacity'   => 'required|integer|min:1|max:100',
            'bus_status' => 'required|in:disponible,en service,en maintenance',
        ]);

        // Création du bus
        $bus = Bus::create([
            'bus_number' => $validated['bus_number'],
            'mack'       => $validated['mack'],
            'capacity'   => (string) $validated['capacity'],
            'bus_status' => $validated['bus_status'],
        ]);

        // Génération automatique des sièges
        for ($i = 1; $i <= $validated['capacity']; $i++) {
            $bus->seats()->create([
                'seat_number' => 'S' . str_pad($i, 2, '0', STR_PAD_LEFT)
            ]);
        }

        return redirect()->route('admin.bus')
                         ->with('success', 'Bus créé avec succès ! ' . $validated['capacity'] . ' sièges générés automatiquement.');
    }

    /**
     * Met à jour un bus
     */
    public function update(Request $request, string $id)
    {
        $bus = Bus::findOrFail($id);

        // Validation des données
        $validated = $request->validate([
            'bus_number' => 'required|string|max:100|unique:buses,bus_number,' . $id,
            'mack'       => 'required|string|max:30',
            'bus_status' => 'required|in:disponible,en service,en maintenance',
        ]);

        $bus->update($validated);

        return redirect()->route('admin.bus')
                         ->with('success', 'Bus mis à jour avec succès !');
    }

    /**
     * Supprime un bus
     */
    public function destroy(string $id)
    {
        $bus = Bus::findOrFail($id);

        // Vérifie si le bus a des déplacements actifs
        $activeDisplacements = $bus->displacements()
                                   ->whereHas('trips', function($q) {
                                       $q->where('travel_status', 'planifié');
                                   })->count();

        if ($activeDisplacements > 0) {
            return redirect()->route('admin.bus')
                             ->with('error', 'Impossible de supprimer ce bus car il a des voyages planifiés !');
        }

        $bus->delete();

        return redirect()->route('admin.bus')
                         ->with('success', 'Bus supprimé avec succès !');
    }
}
