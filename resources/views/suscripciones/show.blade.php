@extends('layouts.app')

@section('title', 'Detalle de Suscripción')

@section('content')
<div class="card shadow-sm border-0 mx-auto" style="max-width: 900px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-white">Detalle de la Suscripción #{{ $suscripcion->id }}</h4>
            <span class="badge {{ $suscripcion->estado === 'activa' ? 'bg-success' : ($suscripcion->estado === 'vencida' ? 'bg-warning text-dark' : 'bg-danger') }}">
                {{ ucfirst($suscripcion->estado) }}
            </span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <p class="text-muted mb-1">Socio</p>
                <h5 class="text-white">{{ $suscripcion->socio->nombre ?? 'N/A' }} {{ $suscripcion->socio->apellidos ?? '' }}</h5>
            </div>

            <div class="col-md-6">
                <p class="text-muted mb-1">Tipo de Membresía</p>
                <h5 class="text-white">{{ $suscripcion->tipoMembresia->nombre ?? 'N/A' }} (${{ number_format($suscripcion->tipoMembresia->precio ?? 0, 2) }})</h5>
            </div>

            <div class="col-md-6">
                <p class="text-muted mb-1">Fecha de Inicio</p>
                <h6 class="text-white">{{ \Carbon\Carbon::parse($suscripcion->fecha_inicio)->format('d/m/Y') }}</h6>
            </div>

            <div class="col-md-6">
                <p class="text-muted mb-1">Fecha de Vencimiento</p>
                <h6 class="text-white">{{ \Carbon\Carbon::parse($suscripcion->fecha_fin)->format('d/m/Y') }}</h6>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <h5 class="text-white mb-3">Historial de Pagos</h5>
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID Pago</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Fecha de Pago</th>
                        <th>Atendido por</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suscripcion->pagos as $pago)
                        <tr>
                            <td>#{{ $pago->id }}</td>
                            <td>${{ number_format($pago->monto, 2) }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($pago->metodo_pago) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</td>
                            <td>{{ $pago->usuario->nombre ?? 'Sistema' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">No hay registros de pago vinculados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('suscripciones.index') }}" class="btn btn-outline-secondary">Regresar</a>
            <a href="{{ route('suscripciones.edit', $suscripcion->id) }}" class="btn btn-warning">Editar</a>        </div>
    </div>
</div>
@endsection