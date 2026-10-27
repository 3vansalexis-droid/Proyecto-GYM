<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuscripcionRequest;
use App\Http\Requests\UpdateSuscripcionRequest;
use App\Models\Pago;
use App\Models\Socio;
use App\Models\Suscripcion;
use App\Models\TipoMembresia;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SuscripcionController extends Controller
{
    public function index(): View
    {
        $this->actualizarSuscripcionesVencidas();

        $suscripciones = Suscripcion::with(['socio', 'tipoMembresia', 'pagos'])
            ->orderByDesc('id')
            ->get();

        return view('suscripciones.index', compact('suscripciones'));
    }

    public function create(): View
    {
        $this->actualizarSuscripcionesVencidas();

        $socios = Socio::with('suscripcionesVigentes')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $tiposMembresia = TipoMembresia::where('activo', true)->orderBy('nombre')->get();

        return view('suscripciones.form', [
            'suscripcion' => new Suscripcion(),
            'socios' => $socios,
            'tiposMembresia' => $tiposMembresia,
        ]);
    }

    public function store(StoreSuscripcionRequest $request): RedirectResponse
    {
        $this->actualizarSuscripcionesVencidas();

        $tipo = TipoMembresia::findOrFail($request->tipo_membresia_id);
        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = $fechaInicio->copy()->addDays($tipo->duracion_dias);
        $liquidarAhora = filled($request->metodo_pago);

        DB::transaction(function () use ($request, $tipo, $fechaInicio, $fechaFin, $liquidarAhora): void {
            $socio = Socio::lockForUpdate()->findOrFail($request->socio_id);

            if ($socio->tieneSuscripcionVigente()) {
                throw ValidationException::withMessages([
                    'socio_id' => 'El socio ya cuenta con una suscripción activa y vigente.',
                ]);
            }

            $suscripcion = Suscripcion::create([
                'socio_id' => $socio->id,
                'tipo_membresia_id' => $tipo->id,
                'fecha_inicio' => $fechaInicio->toDateString(),
                'fecha_fin' => $fechaFin->toDateString(),
                'estado' => $liquidarAhora ? 'activa' : 'cancelada',
            ]);

            if ($liquidarAhora) {
                Pago::create([
                    'suscripcion_id' => $suscripcion->id,
                    'monto' => $tipo->precio,
                    'metodo_pago' => $request->metodo_pago,
                    'fecha_pago' => now(),
                    'usuario_id' => Auth::id(),
                ]);
            }
        });

        $mensaje = $liquidarAhora
            ? 'Suscripción registrada y liquidada correctamente.'
            : 'Suscripción solicitada como pendiente de pago. Liquídala en el módulo de Pagos para activarla.';

        return redirect()
            ->route('suscripciones.index')
            ->with('success', $mensaje);
    }

    public function show(Suscripcion $suscripcion): View
    {
        $suscripcion->load(['socio', 'tipoMembresia', 'pagos.usuario']);

        return view('suscripciones.show', compact('suscripcion'));
    }

    public function edit(Suscripcion $suscripcion): View
    {
        $socios = Socio::with('suscripcionesVigentes')->orderBy('nombre')->get();
        $tiposMembresia = TipoMembresia::orderBy('nombre')->get();

        return view('suscripciones.form', compact('suscripcion', 'socios', 'tiposMembresia'));
    }

    public function update(UpdateSuscripcionRequest $request, Suscripcion $suscripcion): RedirectResponse
    {
        $suscripcion->update($request->validated());

        return redirect()
            ->route('suscripciones.index')
            ->with('success', 'Suscripción actualizada correctamente.');
    }

    public function destroy(Suscripcion $suscripcion): RedirectResponse
    {
        if ($suscripcion->pagos()->exists()) {
            return redirect()
                ->route('suscripciones.index')
                ->with('error', 'No se puede eliminar una suscripción con pagos. Cámbiala a cancelada para conservar el historial.');
        }

        $suscripcion->delete();

        return redirect()
            ->route('suscripciones.index')
            ->with('success', 'Suscripción eliminada correctamente.');
    }

    private function actualizarSuscripcionesVencidas(): void
    {
        Suscripcion::where('estado', 'activa')
            ->whereDate('fecha_fin', '<', today())
            ->update(['estado' => 'vencida']);
    }
}
