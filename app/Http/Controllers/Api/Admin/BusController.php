<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

/**
 * BusController - Gestion des bus (Admin)
 * 
 * Ce controller gère toutes les opérations CRUD
 * sur les bus de la flotte BusTix.
 * Accessible uniquement par les administrateurs.
 */
class BusController extends Controller
{
    /**
     * Affiche la liste de tous les bus
     * GET /api/admin/buses
     */
    public function index()
    {
        try {
            // Récupère tous les bus avec leurs sièges
            $buses = Bus::with('seats')->get();

            return response()->json([
                'success' => true,
                'message' => 'Liste des bus récupérée avec succès',
                'data'    => $buses
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des bus',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée un nouveau bus
     * POST /api/admin/buses
     */
    public function store(Request $request)
    {
        try {
            // Validation des données envoyées
            $validated = $request->validate([
                'bus_number' => 'required|string|max:100|unique:buses',
                'mack'       => 'required|string|max:30',
                'capacity'   => 'required|string|max:10',
                'bus_status' => 'required|string|max:100',
            ]);

            // Création du bus en base de données
            $bus = Bus::create($validated);

            // Génération automatique des sièges selon la capacité
            $capacity = (int) $validated['capacity'];
            for ($i = 1; $i <= $capacity; $i++) {
                $bus->seats()->create([
                    'seat_number' => 'S' . str_pad($i, 2, '0', STR_PAD_LEFT)
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Bus créé avec succès',
                'data'    => $bus->load('seats')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du bus',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un bus spécifique
     * GET /api/admin/buses/{id}
     */
    public function show(string $id)
    {
        try {
            // Récupère le bus avec ses sièges et déplacements
            $bus = Bus::with(['seats', 'displacements'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Bus récupéré avec succès',
                'data'    => $bus
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bus non trouvé'
            ], 404);
        }
    }

    /**
     * Met à jour les informations d'un bus
     * PUT /api/admin/buses/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            // Récupère le bus à modifier
            $bus = Bus::findOrFail($id);

            // Validation des données
            $validated = $request->validate([
                'bus_number' => 'sometimes|string|max:100|unique:buses,bus_number,' . $id,
                'mack'       => 'sometimes|string|max:30',
                'capacity'   => 'sometimes|string|max:10',
                'bus_status' => 'sometimes|string|max:100',
            ]);

            // Mise à jour du bus
            $bus->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Bus mis à jour avec succès',
                'data'    => $bus
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bus non trouvé'
            ], 404);
        }
    }

    /**
     * Supprime un bus
     * DELETE /api/admin/buses/{id}
     */
    public function destroy(string $id)
    {
        try {
            // Récupère le bus à supprimer
            $bus = Bus::findOrFail($id);

            // Suppression du bus (les sièges sont supprimés en cascade)
            $bus->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bus supprimé avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bus non trouvé'
            ], 404);
        }
    }
}
