<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * CustomerController - Gestion des clients (Admin)
 * 
 * Ce controller gère toutes les opérations CRUD
 * sur les clients de BusTix.
 * Accessible uniquement par les administrateurs.
 */
class CustomerController extends Controller
{
    /**
     * Affiche la liste de tous les clients
     * GET /api/admin/customers
     */
    public function index()
    {
        try {
            // Récupère tous les clients avec leur compte utilisateur
            $customers = Customer::with('user')->get();

            return response()->json([
                'success' => true,
                'message' => 'Liste des clients récupérée avec succès',
                'data'    => $customers
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des clients',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée un nouveau client avec son compte utilisateur
     * POST /api/admin/customers
     */
    public function store(Request $request)
    {
        try {
            // Validation des données envoyées
            $validated = $request->validate([
                'name'      => 'required|string|max:100',
                'surname'   => 'required|string|max:100',
                'telephone' => 'required|string|max:10',
                'email'     => 'required|email|unique:customers,email',
                'id_card'   => 'required|integer',
                'password'  => 'required|string|min:8',
            ]);

            // Création du compte utilisateur lié au client
            $user = User::create([
                'name'         => $validated['name'],
                'user_surname' => $validated['surname'],
                'email'        => $validated['email'],
                'telephone'    => $validated['telephone'],
                'password'     => Hash::make($validated['password']),
                'role'         => User::ROLE_CLIENT,
                'actif'        => true,
            ]);

            // Création du profil client lié au compte utilisateur
            $customer = Customer::create([
                'user_id'   => $user->id,
                'name'      => $validated['name'],
                'surname'   => $validated['surname'],
                'telephone' => $validated['telephone'],
                'email'     => $validated['email'],
                'id_card'   => $validated['id_card'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client créé avec succès',
                'data'    => $customer->load('user')
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
                'message' => 'Erreur lors de la création du client',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un client spécifique
     * GET /api/admin/customers/{id}
     */
    public function show(string $id)
    {
        try {
            // Récupère le client avec toutes ses relations
            $customer = Customer::with([
                'user',
                'ticketReservations.trip.displacement',
                'ticketReservations.seat',
                'ticketReservations.payment'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Client récupéré avec succès',
                'data'    => $customer
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client non trouvé'
            ], 404);
        }
    }

    /**
     * Met à jour les informations d'un client
     * PUT /api/admin/customers/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            // Récupère le client à modifier
            $customer = Customer::findOrFail($id);

            // Validation des données
            $validated = $request->validate([
                'name'      => 'sometimes|string|max:100',
                'surname'   => 'sometimes|string|max:100',
                'telephone' => 'sometimes|string|max:10',
                'email'     => 'sometimes|email|unique:customers,email,' . $id,
                'id_card'   => 'sometimes|integer',
                'actif'     => 'sometimes|boolean',
            ]);

            // Mise à jour du profil client
            $customer->update($validated);

            // Mise à jour du compte utilisateur associé
            $customer->user->update([
                'name'         => $validated['name']    ?? $customer->user->name,
                'user_surname' => $validated['surname'] ?? $customer->user->user_surname,
                'telephone'    => $validated['telephone'] ?? $customer->user->telephone,
                'email'        => $validated['email']   ?? $customer->user->email,
                'actif'        => $validated['actif']   ?? $customer->user->actif,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client mis à jour avec succès',
                'data'    => $customer->load('user')
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
                'message' => 'Client non trouvé'
            ], 404);
        }
    }

    /**
     * Supprime un client
     * DELETE /api/admin/customers/{id}
     */
    public function destroy(string $id)
    {
        try {
            // Récupère le client à supprimer
            $customer = Customer::findOrFail($id);

            // Vérifie si le client a des réservations confirmées
            $activeReservations = $customer->ticketReservations()
                                           ->where('status', 'confirmée')
                                           ->count();

            if ($activeReservations > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer ce client car il a '
                                 . $activeReservations
                                 . ' réservation(s) confirmée(s)'
                ], 409);
            }

            // Suppression du compte utilisateur (supprime le client en cascade)
            $customer->user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Client supprimé avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client non trouvé'
            ], 404);
        }
    }
}
