<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirection après connexion selon le rôle
     */
    protected function redirectTo()
    {
        return '/home';
    }

    /**
     * Constructeur - accessible uniquement aux invités
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirection personnalisée selon le rôle après connexion
     */
    protected function authenticated(Request $request, $user)
    {
        // Si l'utilisateur est admin → Dashboard Admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Si l'utilisateur est client → Page d'accueil
        return redirect('/home');
    }
}