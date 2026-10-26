<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Socio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AsistenciasController extends Controller
{
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
}
