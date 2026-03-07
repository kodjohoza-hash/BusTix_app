<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Displacement;
use Illuminate\Http\Request;

/**
 * DisplacementController - Gestion des trajets (Admin)
 * 
 * Ce controller gère toutes les opérations CRUD
 * sur les trajets fixes de BusTix.
 * Ex: Yaoundé → Douala, Douala → Bafoussam...
 * Accessible uniquement par les administrateurs.
 */
class DisplacementController extends Controller
{
    /**
     * Affiche la liste de tous les trajets
     * GET /api/admin/displacements
     */
    public function index()
    {
        try {
            // Récupère tous les trajets avec le bus associé
            $displacements = Displacement::with('bus')->get();

            return response()->json([
                'success' => true,
                'message' => 'Liste des trajets récupérée avec succès',
                'data'    => $displacements
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des trajets',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée un nouveau trajet
     * POST /api/admin/displacements
     */
    public function store(Request $request)
    {
        try {
            // Validation des données envoyées
            $validated = $request->validate([
                'bus_id'            => 'required|exists:buses,id',
                'start_point'       => 'required|string|max:20',
                'destination_point' => 'required|string|max:20',
                'prix'              => 'required|numeric|min:0',
                'distance_km'       => 'required|integer|min:1',
            ]);

            // Création du trajet en base de données
            $displacement = Displacement::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Trajet créé avec succès',
                'data'    => $displacement->load('bus')
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
                'message' => 'Erreur lors de la création du trajet',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un trajet spécifique
     * GET /api/admin/displacements/{id}
     */
    public function show(string $id)
    {
        try {
            // Récupère le trajet avec son bus et ses voyages
            $displacement = Displacement::with(['bus', 'trips'])
                                        ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Trajet récupéré avec succès',
                'data'    => $displacement
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Trajet non trouvé'
            ], 404);
        }
    }

    /**
     * Met à jour les informations d'un trajet
     * PUT /api/admin/displacements/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            // Récupère le trajet à modifier
            $displacement = Displacement::findOrFail($id);

            // Validation des données
            $validated = $request->validate([
                'bus_id'            => 'sometimes|exists:buses,id',
                'start_point'       => 'sometimes|string|max:20',
                'destination_point' => 'sometimes|string|max:20',
                'prix'              => 'sometimes|numeric|min:0',
                'distance_km'       => 'sometimes|integer|min:1',
            ]);

            // Mise à jour du trajet
            $displacement->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Trajet mis à jour avec succès',
                'data'    => $displacement->load('bus')
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
                'message' => 'Trajet non trouvé'
            ], 404);
        }
    }

    /**
     * Supprime un trajet
     * DELETE /api/admin/displacements/{id}
     */
    public function destroy(string $id)
    {
        try {
            // Récupère le trajet à supprimer
            $displacement = Displacement::findOrFail($id);

            // Vérifie si le trajet a des voyages en cours
            $activeTrips = $displacement->trips()
                                        ->where('travel_status', 'planifié')
                                        ->count();

            if ($activeTrips > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer ce trajet car il a des voyages planifiés'
                ], 409);
            }

            // Suppression du trajet
            $displacement->delete();

            return response()->json([
                'success' => true,
                'message' => 'Trajet supprimé avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Trajet non trouvé'
            ], 404);
        }
    }
}
