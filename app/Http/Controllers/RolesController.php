<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Database\QueryException;
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
        $catalogoPermisos = Rol::CATALOGO_PERMISOS;

        return view('roles.create', compact('catalogoPermisos'));
    }

    /**
     * Guarda un nuevo rol con sus permisos seleccionados.
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:50', 'unique:roles,nombre'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'permisos' => ['nullable', 'array'],
            'permisos.*' => ['string', Rule::in(array_keys(Rol::CATALOGO_PERMISOS))],
        ]);

        try {
            $rol = Rol::create([
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'] ?? null,
            ]);

            $rol->sincronizarPermisos($datos['permisos'] ?? []);
        } catch (QueryException) {
            return back()
                ->withInput()
                ->with('error', 'No se pudo crear el rol.');
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol y permisos creados correctamente.');
    }

    /**
     * Muestra un rol con sus permisos y usuarios asignados.
     */
    public function show(Rol $rol): View
    {
        $rol->load('usuarios');
        $catalogoPermisos = Rol::CATALOGO_PERMISOS;
        $permisosActivos = $rol->permisosEfectivos();

        return view('roles.show', compact('rol', 'catalogoPermisos', 'permisosActivos'));
    }

    /**
     * Formulario para editar un rol y sus permisos.
     */
    public function edit(Rol $rol): View
    {
        $catalogoPermisos = Rol::CATALOGO_PERMISOS;
        $permisosActuales = $rol->permisosEfectivos();

        return view('roles.edit', compact('rol', 'catalogoPermisos', 'permisosActuales'));
    }

    /**
     * Actualiza un rol existente y sus permisos.
     */
    public function update(Request $request, Rol $rol): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:50', Rule::unique('roles', 'nombre')->ignore($rol->id)],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'permisos' => ['nullable', 'array'],
            'permisos.*' => ['string', Rule::in(array_keys(Rol::CATALOGO_PERMISOS))],
        ]);

        $esAdminRol = mb_strtolower(trim((string) $rol->nombre)) === 'administrador';

        try {
            $rol->update([
                'nombre' => $esAdminRol ? $rol->nombre : $datos['nombre'],
                'descripcion' => $datos['descripcion'] ?? null,
            ]);

            $rol->sincronizarPermisos($datos['permisos'] ?? []);
        } catch (QueryException) {
            return back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el rol.');
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol y permisos actualizados correctamente.');
    }

    /**
     * Elimina un rol (solo si no tiene usuarios asignados).
     */
    public function destroy(Rol $rol): RedirectResponse
    {
        if (mb_strtolower(trim((string) $rol->nombre)) === 'administrador') {
            return redirect()
                ->route('roles.index')
                ->with('error', 'El rol de Administrador principal no puede eliminarse.');
        }

        if ($rol->usuarios()->exists()) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        try {
            $rol->eliminarPermisosRespaldo();
            $rol->delete();
        } catch (QueryException) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'No se pudo eliminar el rol.');
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}
