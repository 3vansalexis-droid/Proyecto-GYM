<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UsuariosController extends Controller
{
    /**
     * Listado de usuarios.
     */
    public function index(): View
    {
        $usuarios = Usuario::with('rol')
            ->orderBy('nombre')
            ->paginate(15);

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario para crear un usuario.
     */
    public function create(): View
    {
        $roles = Rol::orderBy('nombre')->pluck('nombre', 'id');

        return view('usuarios.create', compact('roles'));
    }

    /**
     * Guarda un nuevo usuario.
     * La contraseña se encripta automáticamente en el modelo (Hash::make).
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'rol_id' => ['required', 'integer', 'exists:roles,id'],
            'nombre' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:usuarios,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'activo' => ['sometimes', 'boolean'],
        ]);

        Usuario::create([
            'rol_id' => $datos['rol_id'],
            'nombre' => $datos['nombre'],
            'email' => $datos['email'],
            'password_hash' => $datos['password'],
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Muestra el detalle de un usuario.
     */
    public function show(Usuario $usuario): View
    {
        $usuario->load('rol');

        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Formulario para editar un usuario.
     */
    public function edit(Usuario $usuario): View
    {
        $roles = Rol::orderBy('nombre')->pluck('nombre', 'id');

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Actualiza un usuario. La contraseña solo cambia si se envía una nueva.
     */
    public function update(Request $request, Usuario $usuario): RedirectResponse
    {
        $datos = $request->validate([
            'rol_id' => ['required', 'integer', 'exists:roles,id'],
            'nombre' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('usuarios', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $usuario->fill([
            'rol_id' => $datos['rol_id'],
            'nombre' => $datos['nombre'],
            'email' => $datos['email'],
            'activo' => $request->boolean('activo'),
        ]);

        if (! empty($datos['password'])) {
            $usuario->password_hash = $datos['password'];
        }

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario.
     */
    public function destroy(Usuario $usuario): RedirectResponse
    {
        if (Auth::id() === $usuario->id) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        if ($usuario->pagos()->exists() || $usuario->asistencias()->exists()) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'El usuario tiene pagos o asistencias registradas. Desactívalo en lugar de eliminarlo.');
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}

