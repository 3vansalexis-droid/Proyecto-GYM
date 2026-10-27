<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Autentica al usuario validando email, password y activo = true.
     */
    public function login(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt([
            'email' => $credenciales['email'],
            'password' => $credenciales['password'],
            'activo' => true,
        ])) {
            $request->session()->regenerate();

            $rutaPorDefecto = match (Auth::user()->rol?->nombre) {
                'Administrador', 'Recepcionista' => route('asistencias.create'),
                default => route('dashboard'),
            };

            return redirect()->intended($rutaPorDefecto);
        }

        $usuario = Usuario::where('email', $credenciales['email'])->first();

        if ($usuario && ! $usuario->activo && Hash::check($credenciales['password'], $usuario->password_hash)) {
            throw ValidationException::withMessages([
                'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
