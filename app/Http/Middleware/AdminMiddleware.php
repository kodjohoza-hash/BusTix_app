<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware AdminMiddleware
 * 
 * Protège les routes réservées aux administrateurs.
 * - Les routes API retournent du JSON
 * - Les routes Web redirigent silencieusement
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->check()) {
            // API → JSON | Web → redirect login
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié. Veuillez vous connecter.'
                ], 401);
            }
            return redirect()->route('login');
        }

        // Vérifie si le compte est actif
        if (!auth()->user()->isActive()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre compte est désactivé.'
                ], 403);
            }
            auth()->logout();
            return redirect()->route('login')
                             ->with('error', 'Votre compte est désactivé.');
        }

        // Vérifie si l'utilisateur est bien un Admin
        if (!auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé.'
                ], 403);
            }
            // Client redirigé vers /home silencieusement 🔒
            return redirect('/home');
        }

        // Tout est OK !
        return $next($request);
    }
}