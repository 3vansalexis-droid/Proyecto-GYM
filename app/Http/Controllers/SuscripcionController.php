<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuscripcionRequest;
use App\Http\Requests\UpdateSuscripcionRequest;
use App\Models\Suscripcion;
use App\Models\Socio;
use App\Models\TipoMembresia;
use App\Models\Pago;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SuscripcionController extends Controller
{
    public function index(): View
    {
        $suscripciones = Suscripcion::with(['socio', 'tipoMembresia', 'pagos'])
            ->orderBy('id', 'desc')
            ->get();

        return view('suscripciones.index', compact('suscripciones'));
    }

    public function create(): View
    {
        $socios = Socio::orderBy('nombre')->get();
        $tiposMembresia = TipoMembresia::where('activo', true)->orderBy('nombre')->get();

        return view('suscripciones.form', [
            'suscripcion' => new Suscripcion(),
            'socios' => $socios,
            'tiposMembresia' => $tiposMembresia,
        ]);
    }

    public function store(StoreSuscripcionRequest $request): RedirectResponse
    {
        $tipo = TipoMembresia::findOrFail($request->tipo_membresia_id);
        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = $fechaInicio->copy()->addDays($tipo->duracion_dias);

        DB::transaction(function () use ($request, $tipo, $fechaInicio, $fechaFin) {
            $suscripcion = Suscripcion::create([
                'socio_id' => $request->socio_id,
                'tipo_membresia_id' => $tipo->id,
                'fecha_inicio' => $fechaInicio->toDateString(),
                'fecha_fin' => $fechaFin->toDateString(),
                'estado' => 'activa',
            ]);

            Pago::create([
                'suscripcion_id' => $suscripcion->id,
                'monto' => $tipo->precio,
                'metodo_pago' => $request->metodo_pago,
                'fecha_pago' => now(),
                'usuario_id' => Auth::id(),
            ]);
        });

        return redirect()->route('suscripciones.index')
            ->with('success', 'Suscripción registrada y pago procesado correctamente.');
    }

    public function show(Suscripcion $suscripcion): View
    {
        $suscripcion->load(['socio', 'tipoMembresia', 'pagos.usuario']);

        return view('suscripciones.show', compact('suscripcion'));
    }

    public function edit(Suscripcion $suscripcion): View
    {
        $socios = Socio::orderBy('nombre')->get();
        $tiposMembresia = TipoMembresia::orderBy('nombre')->get();

        return view('suscripciones.form', compact('suscripcion', 'socios', 'tiposMembresia'));
    }

    public function update(UpdateSuscripcionRequest $request, Suscripcion $suscripcion): RedirectResponse
    {
        $suscripcion->update($request->validated());

        return redirect()->route('suscripciones.index')
            ->with('success', 'Suscripción actualizada correctamente.');
    }

    public function destroy(Suscripcion $suscripcion): RedirectResponse
    {
        DB::transaction(function () use ($suscripcion) {
            $suscripcion->pagos()->delete();
            $suscripcion->delete();
        });

        return redirect()->route('suscripciones.index')
            ->with('success', 'Suscripción y pagos asociados eliminados correctamente.');
    }
}