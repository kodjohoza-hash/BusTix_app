<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche le profil
     */
    public function index()
    {
        return view('pages.profile');
    }

    /**
     * Met à jour les infos personnelles
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'user_surname' => 'required|string|max:255',
            'telephone'    => 'required|string|max:20',
            'email'        => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        // Met à jour aussi le customer
        if ($user->customer) {
            $user->customer->update([
                'name'      => $validated['name'],
                'surname'   => $validated['user_surname'],
                'telephone' => $validated['telephone'],
                'email'     => $validated['email'],
            ]);
        }

        return redirect()->route('profile')
                         ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Change le mot de passe
     */
    public function password(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('profile')
                             ->with('error', 'Mot de passe actuel incorrect !');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile')
                         ->with('success', 'Mot de passe changé avec succès !');
    }
}