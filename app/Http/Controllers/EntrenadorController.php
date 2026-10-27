<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntrenadorRequest;
use App\Http\Requests\UpdateEntrenadorRequest;
use App\Models\Entrenador;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EntrenadorController extends Controller
{
    public function index(): View
    {
        $entrenadores = Entrenador::orderBy('id')->get();

        return view('entrenadores.index', compact('entrenadores'));
    }

    public function create(): View
    {
        return view('entrenadores.form', ['entrenador' => new Entrenador()]);
    }

    public function store(StoreEntrenadorRequest $request): RedirectResponse
    {
        Entrenador::create($request->validated());

        return redirect()->route('entrenadores.index')->with('success', 'Entrenador registrado correctamente.');
    }

    public function show(Entrenador $entrenador): View
    {
        return view('entrenadores.show', compact('entrenador'));
    }

    public function edit(Entrenador $entrenador): View
    {
        return view('entrenadores.form', compact('entrenador'));
    }

    public function update(UpdateEntrenadorRequest $request, Entrenador $entrenador): RedirectResponse
    {
        $entrenador->update($request->validated());

        return redirect()->route('entrenadores.index')->with('success', 'Entrenador actualizado correctamente.');
    }

    public function destroy(Entrenador $entrenador): RedirectResponse
    {
        $entrenador->delete();

        return redirect()->route('entrenadores.index')->with('success', 'Entrenador eliminado correctamente.');
    }
}
