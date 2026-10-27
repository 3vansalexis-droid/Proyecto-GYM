<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Socio;
use App\Models\Usuario;
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
            ->get();

        return view('asistencias.index', compact('asistencias'));
    }

    public function create(): View
    {
        return view('asistencias.recepcion');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'clave_socio' => ['required', 'string'],
        ], [
            'clave_socio.required' => 'Ingresa la clave del socio.',
        ]);

        $socio = Socio::where('clave_socio', $datos['clave_socio'])->first();

        if (! $socio) {
            return back()
                ->withInput()
                ->with('error', 'No se encontró un socio con esa clave.');
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

        Asistencia::create([
            'socio_id' => $socio->id,
            'usuario_id' => Auth::id(),
        ]);

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

        $asistencia->update($datos);

        return redirect()
            ->route('asistencias.index')
            ->with('success', 'Asistencia actualizada correctamente.');
    }

    public function destroy(Asistencia $asistencia): RedirectResponse
    {
        $asistencia->delete();

        return redirect()
            ->route('asistencias.index')
            ->with('success', 'Asistencia eliminada correctamente.');
    }
}
