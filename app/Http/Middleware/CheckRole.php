<?php

namespace App\Http\Middleware;

use App\Models\Rol;
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

        if ($usuario->esAdministrador()) {
            return $next($request);
        }

        $autorizado = false;

        foreach ($roles as $criterio) {
            if (str_starts_with($criterio, 'permiso:')) {
                $clavePermiso = substr($criterio, 8);
                if ($usuario->tienePermiso($clavePermiso)) {
                    $autorizado = true;
                    break;
                }

                continue;
            }

            if (array_key_exists($criterio, Rol::CATALOGO_PERMISOS)) {
                if ($usuario->tienePermiso($criterio)) {
                    $autorizado = true;
                    break;
                }

                continue;
            }

            if ($usuario->rol?->nombre === $criterio) {
                $autorizado = true;
                break;
            }
        }

        if (! $autorizado) {
            abort(403, 'Acceso denegado: No tienes permisos para entrar aquí.');
        }

        return $next($request);
    }
}
