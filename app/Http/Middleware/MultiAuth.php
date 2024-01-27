<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MultiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() || auth()->guard('admin')->check()) {
            return $next($request);
        }

        return response()->json([
            "statut" => false,
            "message" => "Veuillez vous connecter d'abord puis fournir un token pour acceder a cette route"
        ], 401);
    }
}
