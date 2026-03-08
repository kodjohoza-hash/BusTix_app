<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * ClientController Web - Gestion des clients (Admin)
 */
class ClientController extends Controller
{
    /**
     * Affiche la liste de tous les clients
     */
    public function index()
    {
        $customers = Customer::with(['user', 'ticketReservations'])
                             ->withCount('ticketReservations')
                             ->latest()
                             ->paginate(15);

        $totalCustomers  = Customer::count();
        $activeCustomers = Customer::whereHas('user', function($q) {
                               $q->where('actif', true);
                           })->count();

        return view('admin.users', compact(
            'customers',
            'totalCustomers',
            'activeCustomers'
        ));
    }

    /**
     * Active ou désactive un client
     */
    public function toggleStatus(string $id)
    {
        $customer = Customer::findOrFail($id);
        $user     = $customer->user;

        $user->update(['actif' => !$user->actif]);

        $status = $user->actif ? 'activé' : 'désactivé';

        return redirect()->route('admin.users')
                         ->with('success', "Compte client {$status} avec succès !");
    }

    /**
     * Supprime un client
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);

        // Vérifie si le client a des réservations
        if ($customer->ticketReservations()->count() > 0) {
            return redirect()->route('admin.users')
                             ->with('error', 'Impossible de supprimer ce client car il a des réservations !');
        }

        // Supprime le user associé aussi
        $customer->user->delete();
        $customer->delete();

        return redirect()->route('admin.users')
                         ->with('success', 'Client supprimé avec succès !');
    }
}