<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePagoRequest;
use App\Models\Pago;
use App\Models\Suscripcion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function index(): View
    {
        $pagos = Pago::with(['suscripcion.socio', 'suscripcion.tipoMembresia', 'usuario'])
            ->orderByDesc('id')
            ->get();

        return view('pagos.index', compact('pagos'));
    }

    public function create(Request $request): View
    {
        $suscripciones = $this->suscripcionesDisponibles();
        $suscripcionSeleccionada = null;

        if ($request->filled('suscripcion_id')) {
            $suscripcionSeleccionada = $suscripciones->firstWhere('id', (int) $request->query('suscripcion_id'));
        }

        return view('pagos.form', [
            'pago' => new Pago([
                'suscripcion_id' => $suscripcionSeleccionada?->id,
                'monto' => $suscripcionSeleccionada?->saldoPendiente(),
                'fecha_pago' => now(),
            ]),
            'suscripciones' => $suscripciones,
        ]);
    }

    public function store(StorePagoRequest $request): RedirectResponse
    {
        $liquidada = false;

        DB::transaction(function () use ($request, &$liquidada): void {
            $datos = $request->validated();

            Pago::create([
                ...$datos,
                'usuario_id' => Auth::id(),
            ]);

            $suscripcion = Suscripcion::with(['tipoMembresia', 'pagos'])
                ->lockForUpdate()
                ->findOrFail($datos['suscripcion_id']);

            $suscripcion->sincronizarEstadoPorPagos();
            $liquidada = $suscripcion->estaLiquidada();
        });

        $mensaje = $liquidada
            ? 'Pago registrado. La suscripción ha quedado liquidada y activa.'
            : 'Abono registrado correctamente. La suscripción aún tiene saldo pendiente por liquidar.';

        return redirect()
            ->route('pagos.index')
            ->with('success', $mensaje);
    }

    public function show(Pago $pago): View
    {
        $pago->load(['suscripcion.socio', 'suscripcion.tipoMembresia', 'usuario']);

        return view('pagos.show', compact('pago'));
    }

    public function anular(Pago $pago): RedirectResponse
    {
        if ($pago->estaAnulado()) {
            return redirect()
                ->route('pagos.index')
                ->with('error', 'El pago ya se encuentra anulado.');
        }

        $motivo = request()->validate([
            'motivo_anulacion' => ['required', 'string', 'max:255'],
        ])['motivo_anulacion'];

        DB::transaction(function () use ($pago, $motivo): void {
            $pago->update([
                'anulado_at' => now(),
                'anulado_por' => Auth::id(),
                'motivo_anulacion' => $motivo,
            ]);

            $suscripcion = Suscripcion::with(['tipoMembresia', 'pagos'])
                ->lockForUpdate()
                ->findOrFail($pago->suscripcion_id);

            $suscripcion->sincronizarEstadoPorPagos();
        });

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago anulado correctamente. La suscripción pasó a estado pendiente de liquidar.');
    }

    /**
     * Devuelve únicamente las suscripciones con saldo pendiente por liquidar.
     *
     * @return Collection<int, Suscripcion>
     */
    private function suscripcionesDisponibles(): Collection
    {
        return Suscripcion::with(['socio', 'tipoMembresia', 'pagos'])
            ->orderByDesc('id')
            ->get()
            ->filter(fn (Suscripcion $suscripcion) => ! $suscripcion->estaLiquidada())
            ->values();
    }
}
