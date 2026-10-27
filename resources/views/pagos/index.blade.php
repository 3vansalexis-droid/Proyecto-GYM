@extends('layouts.app')

@section('title', 'Pagos')

@section('content')
    <div class="section-heading d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
            <h2>Pagos</h2>
            <p>Consulta el historial de ingresos y conserva la trazabilidad de cada operación.</p>
        </div>
        <a href="{{ route('pagos.create') }}" class="btn btn-primary">Nuevo pago</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    <div class="alert alert-info d-flex align-items-start gap-2" role="status">
        <i class="bi bi-info-circle-fill mt-1"></i>
        <div><strong>Historial financiero protegido.</strong> Los pagos no se eliminan ni se editan; una corrección se registra mediante una anulación.</div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Socio</th>
                        <th>Usuario</th>
                        <th>Monto</th>
                        <th>Metodo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagos as $pago)
                        <tr>
                            <td>{{ $pago->id }}</td>
                            <td>{{ $pago->suscripcion?->socio?->nombre ?? '—' }}</td>
                            <td>{{ $pago->usuario?->nombre ?? '—' }}</td>
                            <td>${{ number_format((float) $pago->monto, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $pago->metodo_pago)) }}</td>
                            <td>{{ optional($pago->fecha_pago)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($pago->estaAnulado())
                                    <span class="badge text-bg-danger">Anulado</span>
                                @else
                                    <span class="badge text-bg-success">Válido</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('pagos.show', $pago) }}" class="btn btn-outline-secondary">Ver</a>
                                    @if (Auth::user()->rol?->nombre === 'Administrador' && ! $pago->estaAnulado())
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#anularPago{{ $pago->id }}">Anular</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No hay pagos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (Auth::user()->rol?->nombre === 'Administrador')
        @foreach ($pagos->whereNull('anulado_at') as $pago)
            <div class="modal fade" id="anularPago{{ $pago->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Anular pago #{{ $pago->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <form method="POST" action="{{ route('pagos.anular', $pago) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <p>El registro se conservará, pero dejará de contar como ingreso válido.</p>
                                <label for="motivo{{ $pago->id }}" class="form-label">Motivo de anulación</label>
                                <textarea id="motivo{{ $pago->id }}" name="motivo_anulacion" class="form-control" rows="3" maxlength="255" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Confirmar anulación</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection
