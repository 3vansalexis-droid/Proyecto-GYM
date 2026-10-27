@extends('layouts.app')

@section('title', isset($suscripcion->id) ? 'Editar suscripción' : 'Nueva suscripción')

@section('content')
    <div class="card mx-auto" style="max-width: 900px;">
        <div class="card-header">{{ isset($suscripcion->id) ? 'Editar suscripción' : 'Registrar nueva suscripción' }}</div>
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ isset($suscripcion->id) ? route('suscripciones.update', $suscripcion) : route('suscripciones.store') }}">
                @csrf
                @if (isset($suscripcion->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Socio</label>
                        <select name="socio_id" class="form-select @error('socio_id') is-invalid @enderror" required>
                            <option value="">-- Seleccionar socio --</option>
                            @foreach ($socios as $socio)
                                @php
                                    $vigente = $socio->suscripcionesVigentes
                                        ->where('id', '!=', $suscripcion->id ?? null)
                                        ->first();
                                @endphp
                                <option
                                    value="{{ $socio->id }}"
                                    @selected(old('socio_id', $suscripcion->socio_id ?? '') == $socio->id)
                                    @disabled($vigente !== null)
                                >
                                    {{ $socio->nombre }} {{ $socio->apellidos ?? '' }} ({{ $socio->clave_socio }})
                                    @if ($vigente)
                                        — [Suscripción vigente hasta {{ $vigente->fecha_fin->format('d/m/Y') }}]
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('socio_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tipo de membresía</label>
                        <select name="tipo_membresia_id" class="form-select" required>
                            <option value="">-- Seleccionar plan --</option>
                            @foreach ($tiposMembresia as $tipo)
                                <option value="{{ $tipo->id }}" {{ old('tipo_membresia_id', $suscripcion->tipo_membresia_id ?? '') == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }} - ${{ number_format($tipo->precio, 2) }} ({{ $tipo->duracion_dias }} días)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="{{ isset($suscripcion->id) ? 'col-md-4' : 'col-md-6' }}">
                        <label class="form-label">Fecha de inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control"
                            min="{{ date('Y-m-d') }}"
                            value="{{ old('fecha_inicio', optional($suscripcion->fecha_inicio ?? null)->format('Y-m-d') ?? date('Y-m-d')) }}" required>
                    </div>

                    @if (!isset($suscripcion->id))
                        <div class="col-md-6">
                            <label class="form-label">Liquidación (Pago inicial)</label>
                            <select name="metodo_pago" class="form-select">
                                <option value="" {{ old('metodo_pago') === '' || old('metodo_pago') === null ? 'selected' : '' }}>
                                    -- Dejar pendiente (Liquidar después en Pagos) --
                                </option>
                                <option value="efectivo" {{ old('metodo_pago') === 'efectivo' ? 'selected' : '' }}>Cobrar ahora · Efectivo</option>
                                <option value="tarjeta_debito" {{ old('metodo_pago') === 'tarjeta_debito' ? 'selected' : '' }}>Cobrar ahora · Tarjeta de débito</option>
                                <option value="tarjeta_credito" {{ old('metodo_pago') === 'tarjeta_credito' ? 'selected' : '' }}>Cobrar ahora · Tarjeta de crédito</option>
                                <option value="transferencia" {{ old('metodo_pago') === 'transferencia' ? 'selected' : '' }}>Cobrar ahora · Transferencia</option>
                            </select>
                            <div class="form-text">Si la dejas pendiente, la suscripción se activará automáticamente en cuanto la liquides en Pagos.</div>
                        </div>
                    @else
                        <div class="col-md-4">
                            <label class="form-label">Fecha fin</label>
                            <input type="date" name="fecha_fin" class="form-control"
                                value="{{ old('fecha_fin', optional($suscripcion->fecha_fin ?? null)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select" required>
                                @foreach (['activa', 'vencida', 'cancelada'] as $estado)
                                    <option value="{{ $estado }}" {{ old('estado', $suscripcion->estado ?? '') === $estado ? 'selected' : '' }}>
                                        {{ ucfirst($estado) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('suscripciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($suscripcion->id) ? 'Guardar cambios' : 'Guardar suscripción' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
