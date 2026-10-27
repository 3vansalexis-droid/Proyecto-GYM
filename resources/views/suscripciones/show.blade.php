@extends('layouts.app')

@section('title', 'Detalle de suscripción')

@section('content')
    <div class="card mx-auto" style="max-width: 900px;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <h2 class="h4 mb-0">Suscripción #{{ $suscripcion->id }}</h2>
                <div class="d-flex gap-2">
                    @if ($suscripcion->estaLiquidada())
                        <span class="badge text-bg-success"><i class="bi bi-check-circle-fill me-1"></i>Liquidada</span>
                    @else
                        <span class="badge text-bg-danger">Saldo pendiente: ${{ number_format($suscripcion->saldoPendiente(), 2) }}</span>
                    @endif
                    <span class="badge text-bg-{{ $suscripcion->estado === 'activa' ? 'success' : ($suscripcion->estado === 'vencida' ? 'warning' : 'secondary') }}">
                        {{ $suscripcion->estado === 'cancelada' && ! $suscripcion->estaLiquidada() ? 'Sin activar' : ucfirst($suscripcion->estado) }}
                    </span>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <p class="text-body-secondary small mb-1">Socio</p>
                    <h5 class="mb-0">{{ $suscripcion->socio->nombre ?? 'N/A' }} {{ $suscripcion->socio->apellidos ?? '' }}</h5>
                </div>

                <div class="col-md-6">
                    <p class="text-body-secondary small mb-1">Tipo de membresía</p>
                    <h5 class="mb-0">{{ $suscripcion->tipoMembresia->nombre ?? 'N/A' }} (${{ number_format($suscripcion->costoTotal(), 2) }})</h5>
                </div>

                <div class="col-md-4">
                    <div class="stat-box">
                        <span class="text-body-secondary small d-block mb-1">Fecha de inicio</span>
                        <strong>{{ optional($suscripcion->fecha_inicio)->format('d/m/Y') }}</strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-box">
                        <span class="text-body-secondary small d-block mb-1">Fecha de vencimiento</span>
                        <strong>{{ optional($suscripcion->fecha_fin)->format('d/m/Y') }}</strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-box">
                        <span class="text-body-secondary small d-block mb-1">Pagado / Pendiente</span>
                        <strong>
                            ${{ number_format($suscripcion->totalPagado(), 2) }} /
                            <span class="{{ $suscripcion->estaLiquidada() ? 'text-success' : 'text-danger' }}">
                                ${{ number_format($suscripcion->saldoPendiente(), 2) }}
                            </span>
                        </strong>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h5 mb-0">Historial de pagos vinculados</h3>
                @if (! $suscripcion->estaLiquidada())
                    <a href="{{ route('pagos.create', ['suscripcion_id' => $suscripcion->id]) }}" class="btn btn-sm btn-success">
                        <i class="bi bi-cash-coin me-1"></i>Liquidar suscripción (${{ number_format($suscripcion->saldoPendiente(), 2) }})
                    </a>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID Pago</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Fecha de pago</th>
                            <th>Atendido por</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suscripcion->pagos as $pago)
                            <tr>
                                <td>#{{ $pago->id }}</td>
                                <td>${{ number_format($pago->monto, 2) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $pago->metodo_pago)) }}</td>
                                <td>{{ optional($pago->fecha_pago)->format('d/m/Y H:i') }}</td>
                                <td>{{ $pago->usuario->nombre ?? 'Sistema' }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $pago->estaAnulado() ? 'danger' : 'success' }}">
                                        {{ $pago->estaAnulado() ? 'Anulado' : 'Válido' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No hay registros de pago vinculados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('suscripciones.index') }}" class="btn btn-outline-secondary">Volver</a>
                <a href="{{ route('suscripciones.edit', $suscripcion->id) }}" class="btn btn-primary">Editar</a>
            </div>
        </div>
    </div>
@endsection
