<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Socio;
use App\Models\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AsistenciasController extends Controller
{
    public function index(): View
    {
        $asistencias = Asistencia::with(['socio', 'usuario'])
            ->orderByDesc('fecha_hora')
            ->paginate(20);

        return view('asistencias.index', compact('asistencias'));
    }

    public function create(): View
    {
        return view('asistencias.recepcion');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'clave_socio' => ['required', 'string', 'max:50'],
        ], [
            'clave_socio.required' => 'Ingresa la clave del socio.',
        ]);

        $clave = trim($datos['clave_socio']);
        $socio = Socio::where('clave_socio', $clave)->first();

        if (! $socio) {
            return back()
                ->withInput()
                ->with('error', 'No se encontró un socio con esa clave.');
        }

        if (! $socio->activo) {
            return back()
                ->withInput()
                ->with('error', 'El socio se encuentra dado de baja (inactivo).');
        }

        $tieneSuscripcionActiva = $socio->suscripciones()
            ->where('estado', 'activa')
            ->whereDate('fecha_fin', '>=', today())
            ->exists();

        if (! $tieneSuscripcionActiva) {
            return back()
                ->withInput()
                ->with('error', 'El socio no tiene una suscripción activa y vigente.');
        }

        try {
            Asistencia::create([
                'socio_id' => $socio->id,
                'usuario_id' => Auth::id(),
                'fecha_hora' => now(),
            ]);
        } catch (QueryException) {
            return back()
                ->withInput()
                ->with('error', 'Error al registrar la asistencia en la base de datos.');
        }

        return back()->with('success', 'Asistencia registrada correctamente.');
    }

    public function show(Asistencia $asistencia): View
    {
        $asistencia->load(['socio', 'usuario']);

        return view('asistencias.show', compact('asistencia'));
    }

    public function edit(Asistencia $asistencia): View
    {
        $socios = Socio::orderBy('apellidos')->orderBy('nombre')->get();
        $usuarios = Usuario::where('activo', true)
            ->orWhere('id', $asistencia->usuario_id)
            ->orderBy('nombre')
            ->get();

        return view('asistencias.form', compact('asistencia', 'socios', 'usuarios'));
    }

    public function update(Request $request, Asistencia $asistencia): RedirectResponse
    {
        $datos = $request->validate([
            'socio_id' => ['required', 'integer', 'exists:socios,id'],
            'usuario_id' => ['required', 'integer', 'exists:usuarios,id'],
            'fecha_hora' => ['required', 'date'],
        ]);

        try {
            $asistencia->update($datos);
        } catch (QueryException) {
            return back()
                ->withInput()
                ->with('error', 'No se pudo actualizar la asistencia.');
        }

        return redirect()
            ->route('asistencias.index')
            ->with('success', 'Asistencia actualizada correctamente.');
    }

    public function destroy(Asistencia $asistencia): RedirectResponse
    {
        try {
            $asistencia->delete();
        } catch (QueryException) {
            return redirect()
                ->route('asistencias.index')
                ->with('error', 'No se pudo eliminar la asistencia.');
        }

        return redirect()
            ->route('asistencias.index')
            ->with('success', 'Asistencia eliminada correctamente.');
    }
}
