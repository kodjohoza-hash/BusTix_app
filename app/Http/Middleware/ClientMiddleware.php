<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware ClientMiddleware
 * 
 * Protège les routes réservées aux clients.
 * Vérifie que l'utilisateur connecté est bien un client
 * et que son compte est actif avant de le laisser passer.
 */
class ClientMiddleware
{
    /**
     * Traitement de la requête entrante
     * 
     * @param Request $request - La requête HTTP
     * @param Closure $next    - La prochaine action à exécuter
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié. Veuillez vous connecter.'
            ], 401);
        }

        // Vérifie si le compte est actif
        if (!auth()->user()->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte est désactivé. Contactez l\'administrateur.'
            ], 403);
        }

        // Vérifie si l'utilisateur est bien un Client
        if (!auth()->user()->isClient()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Cette section est réservée aux clients.'
            ], 403);
        }

        // Tout est OK, on laisse passer la requête
        return $next($request);
    }
}
