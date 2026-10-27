<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuscripcionRequest;
use App\Http\Requests\UpdateSuscripcionRequest;
use App\Models\Suscripcion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SuscripcionController extends Controller
{
    public function index(): View
    {
        $suscripciones = Suscripcion::with(['socio', 'tipoMembresia'])->orderBy('id')->get();

        return view('suscripciones.index', compact('suscripciones'));
    }

    public function create(): View
    {
        return view('suscripciones.form', ['suscripcion' => new Suscripcion()]);
    }

    public function store(StoreSuscripcionRequest $request): RedirectResponse
    {
        Suscripcion::create($request->validated());

        return redirect()->route('suscripciones.index')->with('success', 'Suscripción registrada correctamente.');
    }

    public function show(Suscripcion $suscripcion): View
    {
        return view('suscripciones.show', compact('suscripcion'));
    }

    public function edit(Suscripcion $suscripcion): View
    {
        return view('suscripciones.form', compact('suscripcion'));
    }

    public function update(UpdateSuscripcionRequest $request, Suscripcion $suscripcion): RedirectResponse
    {
        $suscripcion->update($request->validated());

        return redirect()->route('suscripciones.index')->with('success', 'Suscripción actualizada correctamente.');
    }

    public function destroy(Suscripcion $suscripcion): RedirectResponse
    {
        $suscripcion->delete();

        return redirect()->route('suscripciones.index')->with('success', 'Suscripción eliminada correctamente.');
    }
}
