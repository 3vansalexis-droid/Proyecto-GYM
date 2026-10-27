@extends('layouts.app')

@section('title', isset($suscripcion->id) ? 'Editar suscripción' : 'Nueva suscripción')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 900px;">
        <div class="card-body p-4">
            <h4 class="mb-4 text-white">{{ isset($suscripcion->id) ? 'Editar Suscripción' : 'Nueva Suscripción y Pago' }}</h4>

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
                        <select name="socio_id" class="form-select" required>
                            <option value="">-- Seleccionar socio --</option>
                            @foreach ($socios as $socio)
                                <option value="{{ $socio->id }}" {{ old('socio_id', $suscripcion->socio_id ?? '') == $socio->id ? 'selected' : '' }}>
                                    {{ $socio->nombre }} {{ $socio->apellido ?? '' }}
                                </option>
                            @endforeach
                        </select>
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
                            <label class="form-label">Método de pago</label>
                            <select name="metodo_pago" class="form-select" required>
                                <option value="efectivo" {{ old('metodo_pago') === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                <option value="tarjeta_debito" {{ old('metodo_pago') === 'tarjeta_debito' ? 'selected' : '' }}>Tarjeta de débito</option>
                                <option value="tarjeta_credito" {{ old('metodo_pago') === 'tarjeta_credito' ? 'selected' : '' }}>Tarjeta de crédito</option>
                                <option value="transferencia" {{ old('metodo_pago') === 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                            </select>
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
                        {{ isset($suscripcion->id) ? 'Actualizar' : 'Cobrar y Registrar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection