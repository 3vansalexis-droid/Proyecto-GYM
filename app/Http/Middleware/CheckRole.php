<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        if (! $request->user() || $request->user()->rol?->nombre !== $rol) {
            abort(403, 'Acceso denegado: No tienes permisos para entrar aquí.');
        }

        return $next($request);
    }
}
