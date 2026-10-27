<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        if (! $usuario) {
            abort(403, 'Acceso denegado: No tienes permisos para entrar aquí.');
        }

        if (! $usuario->activo) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Tu cuenta ha sido desactivada.']);
        }

        if (! in_array($usuario->rol?->nombre, $roles, true)) {
            abort(403, 'Acceso denegado: No tienes permisos para entrar aquí.');
        }

        return $next($request);
    }
}
