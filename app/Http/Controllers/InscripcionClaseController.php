<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInscripcionClaseRequest;
use App\Http\Requests\UpdateInscripcionClaseRequest;
use App\Models\InscripcionClase;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InscripcionClaseController extends Controller
{
    public function index(): View
    {
        $inscripciones = InscripcionClase::with(['clase', 'socio'])->orderBy('fecha_inscripcion')->get();

        return view('inscripcion-clases.index', compact('inscripciones'));
    }

    public function create(): View
    {
        return view('inscripcion-clases.form', ['inscripcion' => new InscripcionClase()]);
    }

    public function store(StoreInscripcionClaseRequest $request): RedirectResponse
    {
        InscripcionClase::create($request->validated());

        return redirect()->route('inscripcion-clases.index')->with('success', 'Inscripción registrada correctamente.');
    }

    public function show(InscripcionClase $inscripcionClase): View
    {
        return view('inscripcion-clases.show', compact('inscripcionClase'));
    }

    public function edit(InscripcionClase $inscripcionClase): View
    {
        return view('inscripcion-clases.form', compact('inscripcionClase'));
    }

    public function update(UpdateInscripcionClaseRequest $request, InscripcionClase $inscripcionClase): RedirectResponse
    {
        $inscripcionClase->update($request->validated());

        return redirect()->route('inscripcion-clases.index')->with('success', 'Inscripción actualizada correctamente.');
    }

    public function destroy(InscripcionClase $inscripcionClase): RedirectResponse
    {
        $inscripcionClase->delete();

        return redirect()->route('inscripcion-clases.index')->with('success', 'Inscripción eliminada correctamente.');
    }
}
