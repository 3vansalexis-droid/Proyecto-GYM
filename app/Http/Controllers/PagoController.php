<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Models\Pago;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function index(): View
    {
        $pagos = Pago::with(['suscripcion', 'usuario'])->orderBy('id')->get();

        return view('pagos.index', compact('pagos'));
    }

    public function create(): View
    {
        return view('pagos.form', ['pago' => new Pago()]);
    }

    public function store(StorePagoRequest $request): RedirectResponse
    {
        Pago::create($request->validated());

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function show(Pago $pago): View
    {
        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago): View
    {
        return view('pagos.form', compact('pago'));
    }

    public function update(UpdatePagoRequest $request, Pago $pago): RedirectResponse
    {
        $pago->update($request->validated());

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Pago $pago): RedirectResponse
    {
        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado correctamente.');
    }
}
