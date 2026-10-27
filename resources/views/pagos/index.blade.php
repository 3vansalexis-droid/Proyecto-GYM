@extends('layouts.app')

@section('title', 'Pagos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Pagos</h2>
        <a href="{{ route('pagos.create') }}" class="btn btn-primary">Nuevo pago</a>
    </div>

    <div class="card shadow-sm border-0">
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
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('pagos.show', $pago) }}" class="btn btn-outline-secondary">Ver</a>
                                    <a href="{{ route('pagos.edit', $pago) }}" class="btn btn-outline-primary">Editar</a>
                                    <form action="{{ route('pagos.destroy', $pago) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este pago?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No hay pagos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
