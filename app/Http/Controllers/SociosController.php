<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SociosController extends Controller
{
    public function index(): View
    {
        $socios = Socio::orderBy('apellidos')
            ->orderBy('nombre')
            ->paginate(15);

        return view('socios.index', compact('socios'));
    }

    public function create(): View
    {
        return view('socios.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $ultimoId = \App\Models\Socio::max('id') ?? 0;
        $nuevaClave = 'SOC-' . str_pad($ultimoId + 1, 6, '0', STR_PAD_LEFT);

        $datos['clave_socio'] = $nuevaClave;
        $datos['activo'] = $request->boolean('activo', true);
        Socio::create($datos);

        return redirect()
            ->route('socios.index')
            ->with('success', 'Socio creado correctamente.');
    }

    public function show(Socio $socio): View
    {
        $socio->loadCount(['suscripciones', 'asistencias', 'inscripcionClases']);

        return view('socios.show', compact('socio'));
    }

    public function edit(Socio $socio): View
    {
        return view('socios.edit', compact('socio'));
    }

    public function update(Request $request, Socio $socio): RedirectResponse
    {
        $datos = $request->validate([
            'clave_socio' => ['required', 'string', 'max:255', Rule::unique('socios', 'clave_socio')->ignore($socio->id)],
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $datos['activo'] = $request->boolean('activo');
        $socio->update($datos);

        return redirect()
            ->route('socios.index')
            ->with('success', 'Socio actualizado correctamente.');
    }

    public function destroy(Socio $socio): RedirectResponse
    {
        if ($socio->suscripciones()->exists() || $socio->asistencias()->exists() || $socio->inscripcionClases()->exists()) {
            return redirect()
                ->route('socios.index')
                ->with('error', 'El socio tiene registros relacionados. Desactívalo en lugar de eliminarlo.');
        }

        $socio->delete();

        return redirect()
            ->route('socios.index')
            ->with('success', 'Socio eliminado correctamente.');
    }
}
