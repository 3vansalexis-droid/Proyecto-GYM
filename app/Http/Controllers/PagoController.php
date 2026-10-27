<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Models\Pago;
use App\Models\Suscripcion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
        return view('pagos.form', [
            'pago' => new Pago,
            'suscripciones' => $this->suscripcionesDisponibles(),
        ]);
    }

    public function store(StorePagoRequest $request): RedirectResponse
    {
        Pago::create([
            ...$request->validated(),
            'usuario_id' => Auth::id(),
        ]);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function show(Pago $pago): View
    {
        $pago->load(['suscripcion.socio', 'usuario']);

        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago): View
    {
        return view('pagos.form', [
            'pago' => $pago,
            'suscripciones' => $this->suscripcionesDisponibles(),
        ]);
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

    /**
     * @return Collection<int, Suscripcion>
     */
    private function suscripcionesDisponibles(): Collection
    {
        return Suscripcion::with(['socio', 'tipoMembresia'])
            ->orderByDesc('fecha_inicio')
            ->get();
    }
}
