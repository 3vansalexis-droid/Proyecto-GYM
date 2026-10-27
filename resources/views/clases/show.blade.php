@extends('layouts.app')

@section('title', 'Detalle de la clase')

@section('content')
    <div class="card mx-auto" style="max-width: 850px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <span class="text-body-secondary small text-uppercase fw-semibold">Ficha técnica</span>
                    <h2 class="h4 mb-0 fw-bold">{{ $clase->nombre }}</h2>
                </div>
                <span class="badge text-bg-primary px-3 py-2 fs-6">
                    {{ $clase->dia_semana }}
                </span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-box">
                        <span class="text-body-secondary small d-block mb-1">Cupo máximo</span>
                        <h4 class="mb-0 fw-bold">{{ $clase->cupo_maximo }}</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <span class="text-body-secondary small d-block mb-1">Inscritos</span>
                        <h4 class="text-primary mb-0 fw-bold">{{ $clase->inscripciones_count ?? 0 }}</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    @php
                        $lugaresLibres = max(0, $clase->cupo_maximo - ($clase->inscripciones_count ?? 0));
                    @endphp
                    <div class="stat-box">
                        <span class="text-body-secondary small d-block mb-1">Lugares libres</span>
                        <h4 class="{{ $lugaresLibres > 0 ? 'text-success' : 'text-danger' }} mb-0 fw-bold">
                            {{ $lugaresLibres }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="border-start border-3 border-primary ps-3">
                        <p class="text-body-secondary mb-1 small text-uppercase">Entrenador responsable</p>
                        <h5 class="mb-1">
                            {{ $clase->entrenador->nombre ?? 'Sin entrenador' }} {{ $clase->entrenador->apellidos ?? '' }}
                        </h5>
                        <span class="badge text-bg-secondary">
                            {{ $clase->entrenador->especialidad ?? 'General' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border-start border-3 border-info ps-3">
                        <p class="text-body-secondary mb-1 small text-uppercase">Horario programado</p>
                        <h5 class="mb-1">
                            {{ $clase->hora_inicio?->format('H:i') }} - {{ $clase->hora_fin?->format('H:i') }}
                        </h5>
                        <span class="text-body-secondary small">Duración por sesión</span>
                    </div>
                </div>
            </div>

            @php
                $porcentajeOcupado = $clase->cupo_maximo > 0 ? round((($clase->inscripciones_count ?? 0) / $clase->cupo_maximo) * 100) : 0;
            @endphp
            <div class="mb-4">
                <div class="d-flex justify-content-between small text-body-secondary mb-1">
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

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('clases.index') }}" class="btn btn-outline-secondary">Volver</a>
                <a href="{{ route('clases.edit', $clase->id) }}" class="btn btn-primary">Editar</a>
            </div>
        </div>
    </div>
@endsection
