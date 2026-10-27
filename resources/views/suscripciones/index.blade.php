@extends('layouts.app')

@section('title', 'Suscripciones')

@section('content')
    <div class="section-heading">
        <div>
            <h2>Suscripciones</h2>
            <p>Controla las membresías contratadas, su vigencia y su estado de liquidación.</p>
        </div>
        <a href="{{ route('suscripciones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nueva suscripción
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Socio</th>
                        <th>Tipo</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Liquidación</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suscripciones as $suscripcion)
                        <tr>
                            <td>#{{ $suscripcion->id }}</td>
                            <td class="fw-semibold">{{ $suscripcion->socio?->nombre }} {{ $suscripcion->socio?->apellidos }}</td>
                            <td>
                                {{ $suscripcion->tipoMembresia?->nombre ?? '—' }}
                                <small class="text-body-secondary d-block">${{ number_format($suscripcion->costoTotal(), 2) }}</small>
                            </td>
                            <td>{{ optional($suscripcion->fecha_inicio)->format('d/m/Y') }}</td>
                            <td>{{ optional($suscripcion->fecha_fin)->format('d/m/Y') }}</td>
                            <td>
                                @if ($suscripcion->estaLiquidada())
                                    <span class="badge text-bg-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>Liquidada
                                    </span>
                                @elseif ($suscripcion->totalPagado() > 0)
                                    <span class="badge text-bg-warning">
                                        Abonado ${{ number_format($suscripcion->totalPagado(), 2) }} · Resta ${{ number_format($suscripcion->saldoPendiente(), 2) }}
                                    </span>
                                @else
                                    <span class="badge text-bg-danger">
                                        Pendiente (${{ number_format($suscripcion->saldoPendiente(), 2) }})
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge text-bg-{{ $suscripcion->estado === 'activa' ? 'success' : ($suscripcion->estado === 'vencida' ? 'warning' : 'secondary') }}">
                                    {{ $suscripcion->estado === 'cancelada' && ! $suscripcion->estaLiquidada() ? 'Sin activar' : ucfirst($suscripcion->estado) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    @if (! $suscripcion->estaLiquidada())
                                        <a href="{{ route('pagos.create', ['suscripcion_id' => $suscripcion->id]) }}" class="btn btn-success">
                                            <i class="bi bi-cash-coin me-1"></i>Liquidar
                                        </a>
                                    @endif
                                    <a href="{{ route('suscripciones.show', $suscripcion) }}" class="btn btn-outline-secondary">Ver</a>
                                    <a href="{{ route('suscripciones.edit', $suscripcion) }}" class="btn btn-outline-primary">Editar</a>
                                    <form action="{{ route('suscripciones.destroy', $suscripcion) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar esta suscripción?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No hay suscripciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
