<?php

namespace App\Http\Controllers;

use App\Models\TipoMembresia;
use Illuminate\Http\Request;

class TipoMembresiaController extends Controller
{
    public function index()
    {
        $tiposMembresia = TipoMembresia::orderBy('id', 'desc')->paginate(10);
        return view('tipos_membresia.index', compact('tiposMembresia'));
    }

    public function create()
    {
        return view('tipos_membresia.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:tipos_membresia,nombre',
            'duracion_dias' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'activo' => 'nullable|boolean',
        ], [
            'nombre.required' => 'El nombre del plan es obligatorio.',
            'nombre.unique' => 'Ya existe un plan con este nombre.',
            'duracion_dias.required' => 'Debes indicar la duración en días.',
            'duracion_dias.min' => 'La duración debe ser de al menos 1 día.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
        ]);

        $validated['activo'] = $request->has('activo');

        TipoMembresia::create($validated);

        return redirect()->route('tipos-membresia.index')
            ->with('success', 'Tipo de membresía registrado correctamente.');
    }

    public function show(TipoMembresia $tipos_membresium)
    {
        $tipoMembresia = $tipos_membresium;
        return view('tipos_membresia.show', compact('tipoMembresia'));
    }

    public function edit(TipoMembresia $tipos_membresium)
    {
        $tipoMembresia = $tipos_membresium;
        return view('tipos_membresia.edit', compact('tipoMembresia'));
    }

    public function update(Request $request, TipoMembresia $tipos_membresium)
    {
        $tipoMembresia = $tipos_membresium;

        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:tipos_membresia,nombre,' . $tipoMembresia->id,
            'duracion_dias' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'activo' => 'nullable|boolean',
        ], [
            'nombre.required' => 'El nombre del plan es obligatorio.',
            'nombre.unique' => 'Ya existe un plan con este nombre.',
            'duracion_dias.required' => 'Debes indicar la duración en días.',
            'duracion_dias.min' => 'La duración debe ser de al menos 1 día.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
        ]);

        $validated['activo'] = $request->has('activo');

        $tipoMembresia->update($validated);

        return redirect()->route('tipos-membresia.index')
            ->with('success', 'Tipo de membresía actualizado correctamente.');
    }

    public function destroy(TipoMembresia $tipos_membresium)
    {
        $tipoMembresia = $tipos_membresium;

        if ($tipoMembresia->suscripciones()->count() > 0) {
            return redirect()->route('tipos-membresia.index')
                ->with('error', 'No se puede eliminar: existen suscripciones vinculadas a este plan.');
        }

        $tipoMembresia->delete();

        return redirect()->route('tipos-membresia.index')
            ->with('success', 'Tipo de membresía eliminado correctamente.');
    }
}