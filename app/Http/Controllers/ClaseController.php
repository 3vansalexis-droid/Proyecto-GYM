<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaseRequest;
use App\Http\Requests\UpdateClaseRequest;
use App\Models\Clase;
use App\Models\Entrenador;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClaseController extends Controller
{
    public function index(): View
    {
        $clases = Clase::with('entrenador')
            ->withCount('inscripciones')
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        return view('clases.index', compact('clases'));
    }

    public function create(): View
    {
        $entrenadores = Entrenador::orderBy('nombre')->get();

        return view('clases.form', [
            'clase' => new Clase(),
            'entrenadores' => $entrenadores,
        ]);
    }

    public function store(StoreClaseRequest $request): RedirectResponse
    {
        Clase::create($request->validated());

        return redirect()->route('clases.index')->with('success', 'Clase registrada correctamente.');
    }

    public function show($id): View
    {
        $clase = Clase::with('entrenador')->withCount('inscripciones')->findOrFail($id);

        return view('clases.show', compact('clase'));
    }

    public function edit($id): View
    {
        $clase = Clase::findOrFail($id);
        $entrenadores = Entrenador::orderBy('nombre')->get();

        return view('clases.form', compact('clase', 'entrenadores'));
    }

    public function update(UpdateClaseRequest $request, $id): RedirectResponse
    {
        $clase = Clase::findOrFail($id);
        $datos = $request->validated();

        if ((int) $datos['cupo_maximo'] < $clase->inscripciones()->count()) {
            return back()
                ->withInput()
                ->withErrors(['cupo_maximo' => 'El cupo no puede ser menor que los socios inscritos actualmente.']);
        }

        $clase->update($datos);

        return redirect()->route('clases.index')->with('success', 'Clase actualizada correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        $clase = Clase::withCount('inscripciones')->findOrFail($id);

        if ($clase->inscripciones_count > 0) {
            return redirect()->route('clases.index')
                ->with('error', 'No se puede eliminar la clase porque ya tiene socios inscritos.');
        }

        $clase->delete();

        return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
    }
}