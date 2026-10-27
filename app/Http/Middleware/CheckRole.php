<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->rol?->nombre, $roles, true)) {
            abort(403, 'Acceso denegado: No tienes permisos para entrar aquí.');
        }

        return $next($request);
    }
}
