@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="section-heading d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <h2>Resumen general</h2>
            <p>Consulta el estado operativo de tu gimnasio.</p>
        </div>
        <span class="badge rounded-pill text-bg-light px-3 py-2"><i class="bi bi-circle-fill text-success me-1"></i>Sistema operativo</span>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                    <div class="text-body-secondary small mt-3">Socios registrados</div>
                    <div class="stat-value">{{ $socios_count }}</div>
                    <div class="text-success small mt-2"><i class="bi bi-arrow-up-right me-1"></i>{{ $socios_activos }} activos</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
                    <div class="text-body-secondary small mt-3">Suscripciones activas</div>
                    <div class="stat-value">{{ $suscripciones_activas }}</div>
                    <div class="text-info small mt-2">Cupo operativo</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                    <div class="text-body-secondary small mt-3">Pagos de hoy</div>
                    <div class="stat-value">{{ $pagos_hoy }}</div>
                    <div class="text-primary small mt-2">Recaudación del día</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="stat-icon"><i class="bi bi-calendar-event"></i></div>
                    <div class="text-body-secondary small mt-3">Clases programadas</div>
                    <div class="stat-value">{{ $clases_count }}</div>
                    <div class="text-warning small mt-2">{{ $entrenadores_count }} entrenadores</div>
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
                        @php($rol = Auth::user()->rol?->nombre)
                        @if (in_array($rol, ['Administrador', 'Recepcionista'], true))
                            <a href="{{ route('socios.index') }}" class="btn btn-outline-primary">Socios</a>
                            <a href="{{ route('suscripciones.index') }}" class="btn btn-outline-primary">Suscripciones</a>
                            <a href="{{ route('pagos.index') }}" class="btn btn-outline-primary">Pagos</a>
                            <a href="{{ route('asistencias.index') }}" class="btn btn-outline-primary">Asistencias</a>
                        @endif
                        @if (in_array($rol, ['Administrador', 'Entrenador'], true))
                            <a href="{{ route('clases.index') }}" class="btn btn-outline-primary">Clases</a>
                        @endif
                        @if ($rol === 'Administrador')
                            <a href="{{ route('tipos-membresia.index') }}" class="btn btn-outline-primary">Tipos de membresía</a>
                            <a href="{{ route('entrenadores.index') }}" class="btn btn-outline-primary">Entrenadores</a>
                            <a href="{{ route('inscripciones.index') }}" class="btn btn-outline-primary">Inscripciones</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
