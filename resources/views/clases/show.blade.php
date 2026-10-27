@extends('layouts.app')

@section('title', 'Detalle de la Clase')

@section('content')
<div class="card shadow-sm border-0 mx-auto" style="max-width: 850px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-3 mb-4">
            <div>
                <span class="text-muted small text-uppercase fw-semibold">Ficha Técnica</span>
                <h3 class="mb-0 text-white fw-bold">{{ $clase->nombre }}</h3>
            </div>
            <span class="badge bg-primary px-3 py-2 fs-6">
                {{ $clase->dia_semana }}
            </span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="p-3 rounded bg-dark border border-secondary text-center">
                    <span class="text-muted small d-block mb-1">Cupo Máximo</span>
                    <h4 class="text-white mb-0 fw-bold">{{ $clase->cupo_maximo }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded bg-dark border border-secondary text-center">
                    <span class="text-muted small d-block mb-1">Inscritos</span>
                    <h4 class="text-info mb-0 fw-bold">{{ $clase->inscripciones_count ?? 0 }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                @php
                    $lugaresLibres = max(0, $clase->cupo_maximo - ($clase->inscripciones_count ?? 0));
                @endphp
                <div class="p-3 rounded bg-dark border border-secondary text-center">
                    <span class="text-muted small d-block mb-1">Lugares Libres</span>
                    <h4 class="{{ $lugaresLibres > 0 ? 'text-success' : 'text-danger' }} mb-0 fw-bold">
                        {{ $lugaresLibres }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="border-start border-3 border-primary ps-3">
                    <p class="text-muted mb-1 small text-uppercase">Entrenador Responsable</p>
                    <h5 class="text-white mb-1">
                        {{ $clase->entrenador->nombre ?? 'Sin entrenador' }} {{ $clase->entrenador->apellido ?? '' }}
                    </h5>
                    <span class="badge bg-secondary">
                        {{ $clase->entrenador->especialidad ?? 'General' }}
                    </span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border-start border-3 border-info ps-3">
                    <p class="text-muted mb-1 small text-uppercase">Horario Programado</p>
                    <h5 class="text-white mb-1">
                        {{ substr($clase->hora_inicio, 0, 5) }} - {{ substr($clase->hora_fin, 0, 5) }}
                    </h5>
                    <span class="text-muted small">Duración estimada por sesión</span>
                </div>
            </div>
        </div>

        @php
            $porcentajeOcupado = $clase->cupo_maximo > 0 ? round((($clase->inscripciones_count ?? 0) / $clase->cupo_maximo) * 100) : 0;
        @endphp
        <div class="mb-4">
            <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Ocupación de la clase</span>
                <span>{{ $porcentajeOcupado }}%</span>
            </div>
            <div class="progress" style="height: 8px;">
                <div class="progress-bar {{ $porcentajeOcupado >= 100 ? 'bg-danger' : ($porcentajeOcupado >= 75 ? 'bg-warning' : 'bg-primary') }}" 
                     role="progressbar" 
                     style="width: {{ min(100, $porcentajeOcupado) }}%">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-3 border-top border-secondary">
            <a href="{{ route('clases.index') }}" class="btn btn-outline-secondary px-4">Volver</a>
            <a href="{{ route('clases.edit', $clase->id) }}" class="btn btn-warning px-4">Editar</a>
        </div>
    </div>
</div>
@endsection