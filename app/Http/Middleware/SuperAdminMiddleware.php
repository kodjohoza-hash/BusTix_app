<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Non authentifié'], 401);
            }
            return redirect()->route('login');
        }

        if (!auth()->user()->isSuperAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Accès refusé - Super Admin requis'], 403);
            }
            // Redirige vers le dashboard guichet si c'est un guichet
            if (auth()->user()->isGuichet()) {
                return redirect()->route('guichet.dashboard');
            }
            return redirect()->route('home');
        }

        return $next($request);
    }
}