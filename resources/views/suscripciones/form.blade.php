@extends('layouts.app')

@section('title', isset($suscripcion->id) ? 'Editar suscripción' : 'Nueva suscripción')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 900px;">
        <div class="card-body p-4">
            <form method="POST" action="{{ isset($suscripcion->id) ? route('suscripciones.update', $suscripcion) : route('suscripciones.store') }}">
                @csrf
                @if (isset($suscripcion->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Socio</label>
                        <input type="number" name="socio_id" class="form-control" value="{{ old('socio_id', $suscripcion->socio_id ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de membresía</label>
                        <input type="number" name="tipo_membresia_id" class="form-control" value="{{ old('tipo_membresia_id', $suscripcion->tipo_membresia_id ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', optional($suscripcion->fecha_inicio ?? null)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', optional($suscripcion->fecha_fin ?? null)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select" required>
                            @foreach (['activa', 'vencida', 'cancelada'] as $estado)
                                <option value="{{ $estado }}" {{ old('estado', $suscripcion->estado ?? '') === $estado ? 'selected' : '' }}>{{ ucfirst($estado) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('suscripciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
