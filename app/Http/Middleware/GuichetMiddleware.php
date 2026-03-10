<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuichetMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Non authentifié'], 401);
            }
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Accès refusé'], 403);
            }
            return redirect()->route('home');
        }

        return $next($request);
    }
}