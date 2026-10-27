<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SociosController extends Controller
{
    public function index(): View
    {
        $this->normalizarClavesExistentes();

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
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:socios,email'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        try {
            DB::transaction(function () use ($datos, $request): void {
                $socio = Socio::create([
                    ...$datos,
                    'clave_socio' => 'TMP-' . Str::uuid(),
                    'activo' => $request->boolean('activo', true),
                ]);

                $socio->update([
                    'clave_socio' => Socio::generarClave($socio->id),
                ]);
            });
        } catch (QueryException) {
            return back()
                ->withInput()
                ->with('error', 'Error al registrar el socio en la base de datos.');
        }

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
            'clave_socio' => ['required', 'string', 'max:50', Rule::unique('socios', 'clave_socio')->ignore($socio->id)],
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('socios', 'email')->ignore($socio->id)],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        try {
            $datos['activo'] = $request->boolean('activo');
            $socio->update($datos);
        } catch (QueryException) {
            return back()
                ->withInput()
                ->with('error', 'No se pudo actualizar la información del socio.');
        }

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

        try {
            $socio->delete();
        } catch (QueryException) {
            return redirect()
                ->route('socios.index')
                ->with('error', 'No se puede eliminar el socio porque tiene registros asociados.');
        }

        return redirect()
            ->route('socios.index')
            ->with('success', 'Socio eliminado correctamente.');
    }

    /**
     * Unifica claves antiguas de 6 dígitos (ej. SOC-000006) al formato de 3 dígitos (SOC-006).
     */
    private function normalizarClavesExistentes(): void
    {
        $socios = Socio::where('clave_socio', 'like', 'SOC-000%')->get();

        foreach ($socios as $socio) {
            if (preg_match('/^SOC-0*(\d+)$/', $socio->clave_socio, $coincidencias)) {
                $claveUnificada = Socio::generarClave((int) $coincidencias[1]);

                $yaExiste = Socio::where('clave_socio', $claveUnificada)
                    ->where('id', '!=', $socio->id)
                    ->exists();

                if (! $yaExiste && $socio->clave_socio !== $claveUnificada) {
                    $socio->update(['clave_socio' => $claveUnificada]);
                }
            }
        }
    }
}
