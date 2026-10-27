<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RolesController extends Controller
{
    /**
     * Listado de roles.
     */
    public function index(): View
    {
        $roles = Rol::withCount('usuarios')
            ->orderBy('nombre')
            ->paginate(15);

        return view('roles.index', compact('roles'));
    }

    /**
     * Formulario para crear un rol.
     */
    public function create(): View
    {
        return view('roles.create');
    }

    /**
     * Guarda un nuevo rol.
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:50', 'unique:roles,nombre'],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        Rol::create($datos);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol creado correctamente.');
    }

    /**
     * Muestra un rol con sus usuarios.
     */
    public function show(Rol $rol): View
    {
        $rol->load('usuarios');

        return view('roles.show', compact('rol'));
    }

    /**
     * Formulario para editar un rol.
     */
    public function edit(Rol $rol): View
    {
        return view('roles.edit', compact('rol'));
    }

    /**
     * Actualiza un rol existente.
     */
    public function update(Request $request, Rol $rol): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:50', Rule::unique('roles', 'nombre')->ignore($rol->id)],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        $rol->update($datos);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Elimina un rol (solo si no tiene usuarios asignados).
     */
    public function destroy(Rol $rol): RedirectResponse
    {
        if ($rol->usuarios()->exists()) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        $rol->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}

