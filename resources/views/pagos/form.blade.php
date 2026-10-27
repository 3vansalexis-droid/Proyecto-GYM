@extends('layouts.app')

@section('title', isset($pago->id) ? 'Editar pago' : 'Nuevo pago')

@section('content')
    <div class="card mx-auto" style="max-width: 900px;">
        <div class="card-header">Registrar ingreso / Liquidar suscripción</div>
        <div class="card-body p-4">
            <div class="alert alert-info">
                Al cubrir el saldo pendiente de la suscripción, ésta pasará automáticamente a estado <strong>Liquidada y Activa</strong>.
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ isset($pago->id) ? route('pagos.update', $pago) : route('pagos.store') }}">
                @csrf
                @if (isset($pago->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Suscripción pendiente por liquidar</label>
                        <select name="suscripcion_id" id="suscripcion_id" class="form-select" required>
                            <option value="">-- Seleccionar suscripción --</option>
                            @foreach ($suscripciones as $suscripcion)
                                <option
                                    value="{{ $suscripcion->id }}"
                                    data-saldo="{{ number_format($suscripcion->saldoPendiente(), 2, '.', '') }}"
                                    {{ (string) old('suscripcion_id', $pago->suscripcion_id ?? '') === (string) $suscripcion->id ? 'selected' : '' }}
                                >
                                    #{{ $suscripcion->id }} - {{ $suscripcion->socio?->nombre }} {{ $suscripcion->socio?->apellidos }}
                                    · {{ $suscripcion->tipoMembresia?->nombre }}
                                    (Pendiente: ${{ number_format($suscripcion->saldoPendiente(), 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('suscripcion_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Monto a pagar</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0.01"
                            name="monto"
                            id="monto"
                            class="form-control @error('monto') is-invalid @enderror"
                            value="{{ old('monto', $pago->monto !== null ? number_format((float) $pago->monto, 2, '.', '') : '') }}"
                            required
                        >
                        @error('monto')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Método de pago</label>
                        <select name="metodo_pago" class="form-select" required>
                            @foreach (['efectivo', 'tarjeta_debito', 'tarjeta_credito', 'transferencia'] as $metodo)
                                <option value="{{ $metodo }}" {{ old('metodo_pago', $pago->metodo_pago ?? '') === $metodo ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $metodo)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fecha de pago</label>
                        <input
                            type="datetime-local"
                            name="fecha_pago"
                            class="form-control"
                            value="{{ old('fecha_pago', optional($pago->fecha_pago ?? now())->format('Y-m-d\TH:i')) }}"
                            required
                        >
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary" @disabled($suscripciones->isEmpty())>
                        <i class="bi bi-check2-circle me-1"></i>Liquidar pago
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectSuscripcion = document.getElementById('suscripcion_id');
            const inputMonto = document.getElementById('monto');

            selectSuscripcion?.addEventListener('change', function () {
                const option = this.options[this.selectedIndex];
                const saldo = option?.dataset?.saldo;
                if (saldo) {
                    inputMonto.value = saldo;
                }
            });
        });
    </script>
@endsection
