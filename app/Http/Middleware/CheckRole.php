<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user || !$user->rol || !in_array($user->rol->nombre, $roles)) {
            abort(403, 'Acceso denegado');
        }

        return $next($request);
    }
}
