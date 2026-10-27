<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaseRequest;
use App\Http\Requests\UpdateClaseRequest;
use App\Models\Clase;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClaseController extends Controller
{
    public function index(): View
    {
        $clases = Clase::with('entrenador')->orderBy('dia_semana')->orderBy('hora_inicio')->get();

        return view('clases.index', compact('clases'));
    }

    public function create(): View
    {
        return view('clases.form', ['clase' => new Clase()]);
    }

    public function store(StoreClaseRequest $request): RedirectResponse
    {
        Clase::create($request->validated());

        return redirect()->route('clases.index')->with('success', 'Clase registrada correctamente.');
    }

    public function show(Clase $clase): View
    {
        return view('clases.show', compact('clase'));
    }

    public function edit(Clase $clase): View
    {
        return view('clases.form', compact('clase'));
    }

    public function update(UpdateClaseRequest $request, Clase $clase): RedirectResponse
    {
        $clase->update($request->validated());

        return redirect()->route('clases.index')->with('success', 'Clase actualizada correctamente.');
    }

    public function destroy(Clase $clase): RedirectResponse
    {
        $clase->delete();

        return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
    }
}
