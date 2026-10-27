@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Socios</div>
                    <div class="display-6 fw-semibold mt-2">{{ $socios_count }}</div>
                    <div class="text-success small">{{ $socios_activos }} activos</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Suscripciones activas</div>
                    <div class="display-6 fw-semibold mt-2">{{ $suscripciones_activas }}</div>
                    <div class="text-info small">Cupo operativo</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Pagos hoy</div>
                    <div class="display-6 fw-semibold mt-2">{{ $pagos_hoy }}</div>
                    <div class="text-primary small">Recaudación del día</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Clases</div>
                    <div class="display-6 fw-semibold mt-2">{{ $clases_count }}</div>
                    <div class="text-warning small">{{ $entrenadores_count }} entrenadores</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Resumen operativo</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Asistencias de hoy</span>
                            <strong>{{ $asistencias_hoy }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Socios registrados</span>
                            <strong>{{ $socios_count }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Clases programadas</span>
                            <strong>{{ $clases_count }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Accesos rápidos</h2>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('socios.index') }}" class="btn btn-outline-primary">Socios</a>
                        <a href="{{ route('suscripciones.index') }}" class="btn btn-outline-primary">Suscripciones</a>
                        <a href="{{ route('pagos.index') }}" class="btn btn-outline-primary">Pagos</a>
                        <a href="{{ route('asistencias.index') }}" class="btn btn-outline-primary">Asistencias</a>
                        <a href="{{ route('clases.index') }}" class="btn btn-outline-primary">Clases</a>
                        <a href="{{ route('entrenadores.index') }}" class="btn btn-outline-primary">Entrenadores</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
