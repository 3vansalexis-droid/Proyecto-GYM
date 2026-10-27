<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAsistenciaRequest;
use App\Http\Requests\UpdateAsistenciaRequest;
use App\Models\Asistencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AsistenciaController extends Controller
{
    public function index(): View
    {
        $asistencias = Asistencia::with(['socio', 'usuario'])->orderByDesc('fecha_hora')->get();

        return view('asistencias.index', compact('asistencias'));
    }

    public function create(): View
    {
        return view('asistencias.form', ['asistencia' => new Asistencia()]);
    }

    public function store(StoreAsistenciaRequest $request): RedirectResponse
    {
        Asistencia::create($request->validated());

        return redirect()->route('asistencias.index')->with('success', 'Asistencia registrada correctamente.');
    }

    public function show(Asistencia $asistencia): View
    {
        return view('asistencias.show', compact('asistencia'));
    }

    public function edit(Asistencia $asistencia): View
    {
        return view('asistencias.form', compact('asistencia'));
    }

    public function update(UpdateAsistenciaRequest $request, Asistencia $asistencia): RedirectResponse
    {
        $asistencia->update($request->validated());

        return redirect()->route('asistencias.index')->with('success', 'Asistencia actualizada correctamente.');
    }

    public function destroy(Asistencia $asistencia): RedirectResponse
    {
        $asistencia->delete();

        return redirect()->route('asistencias.index')->with('success', 'Asistencia eliminada correctamente.');
    }
}
