<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSocioRequest;
use App\Http\Requests\UpdateSocioRequest;
use App\Models\Socio;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SocioController extends Controller
{
    public function index(): View
    {
        $socios = Socio::orderBy('id')->get();

        return view('socios.index', compact('socios'));
    }

    public function create(): View
    {
        return view('socios.form', ['socio' => new Socio()]);
    }

    public function store(StoreSocioRequest $request): RedirectResponse
    {
        Socio::create($request->validated());

        return redirect()->route('socios.index')->with('success', 'Socio registrado correctamente.');
    }

    public function show(Socio $socio): View
    {
        return view('socios.show', compact('socio'));
    }

    public function edit(Socio $socio): View
    {
        return view('socios.form', compact('socio'));
    }

    public function update(UpdateSocioRequest $request, Socio $socio): RedirectResponse
    {
        $socio->update($request->validated());

        return redirect()->route('socios.index')->with('success', 'Socio actualizado correctamente.');
    }

    public function destroy(Socio $socio): RedirectResponse
    {
        $socio->delete();

        return redirect()->route('socios.index')->with('success', 'Socio eliminado correctamente.');
    }
}
