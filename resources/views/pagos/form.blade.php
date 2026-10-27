@extends('layouts.app')

@section('title', isset($pago->id) ? 'Editar pago' : 'Nuevo pago')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 900px;">
        <div class="card-body p-4">
            <form method="POST" action="{{ isset($pago->id) ? route('pagos.update', $pago) : route('pagos.store') }}">
                @csrf
                @if (isset($pago->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Suscripción</label>
                        <select name="suscripcion_id" class="form-select" required>
                            <option value="">-- Seleccionar suscripción --</option>
                            @foreach ($suscripciones as $suscripcion)
                                <option value="{{ $suscripcion->id }}" {{ (string) old('suscripcion_id', $pago->suscripcion_id ?? '') === (string) $suscripcion->id ? 'selected' : '' }}>
                                    #{{ $suscripcion->id }} - {{ $suscripcion->socio?->nombre }} {{ $suscripcion->socio?->apellidos }} · {{ $suscripcion->tipoMembresia?->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('suscripcion_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Monto</label>
                        <input type="number" step="0.01" name="monto" class="form-control" value="{{ old('monto', $pago->monto ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Método de pago</label>
                        <select name="metodo_pago" class="form-select" required>
                            @foreach (['efectivo', 'tarjeta_debito', 'tarjeta_credito', 'transferencia'] as $metodo)
                                <option value="{{ $metodo }}" {{ old('metodo_pago', $pago->metodo_pago ?? '') === $metodo ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $metodo)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de pago</label>
                        <input type="datetime-local" name="fecha_pago" class="form-control" value="{{ old('fecha_pago', optional($pago->fecha_pago ?? null)->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
