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
        $entrenadores = Entrenador::orderBy('id', 'desc')->get();

        return view('entrenadores.index', compact('entrenadores'));
    }

    public function create(): View
    {
        return view('entrenadores.form', ['entrenador' => new Entrenador()]);
    }

    public function store(StoreEntrenadorRequest $request): RedirectResponse
    {
        Entrenador::create([
            ...$request->validated(),
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('entrenadores.index')
            ->with('success', 'Entrenador registrado correctamente.');
    }

    public function show($id): View
    {
        $entrenador = Entrenador::with('clases')->findOrFail($id);

        return view('entrenadores.show', compact('entrenador'));
    }

    public function edit($id): View
    {
        $entrenador = Entrenador::findOrFail($id);

        return view('entrenadores.form', compact('entrenador'));
    }

    public function update(UpdateEntrenadorRequest $request, $id): RedirectResponse
    {
        $entrenador = Entrenador::findOrFail($id);
        $entrenador->update([
            ...$request->validated(),
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('entrenadores.index')
            ->with('success', 'Entrenador actualizado correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        $entrenador = Entrenador::withCount('clases')->findOrFail($id);

        if ($entrenador->clases_count > 0) {
            return redirect()->route('entrenadores.index')
                ->with('error', 'No se puede eliminar el entrenador porque tiene clases asignadas.');
        }

        $entrenador->delete();

        return redirect()->route('entrenadores.index')
            ->with('success', 'Entrenador eliminado correctamente.');
    }
}