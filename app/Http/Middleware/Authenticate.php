<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $guard)
    {
        if ($this->auth->guard($guard)->check()) {
            return $next($request);
        }
        return response()->json([
            'unauthorized' => 'Veuillez vous connecter d\'abord puis fournir un token pour accéder à cette route.'
        ], 401);
    }
}
