@extends('layouts.app')

@section('title', 'Suscripciones')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Suscripciones</h2>
        <a href="{{ route('suscripciones.create') }}" class="btn btn-primary">Nueva suscripción</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Socio</th>
                        <th>Tipo</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suscripciones as $suscripcion)
                        <tr>
                            <td>{{ $suscripcion->id }}</td>
                            <td>{{ $suscripcion->socio?->nombre }} {{ $suscripcion->socio?->apellidos }}</td>
                            <td>{{ $suscripcion->tipoMembresia?->nombre ?? '—' }}</td>
                            <td>{{ optional($suscripcion->fecha_inicio)->format('d/m/Y') }}</td>
                            <td>{{ optional($suscripcion->fecha_fin)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $suscripcion->estado === 'activa' ? 'success' : ($suscripcion->estado === 'vencida' ? 'warning text-dark' : 'secondary') }}">
                                    {{ ucfirst($suscripcion->estado) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
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
                            <td colspan="7" class="text-center py-4 text-muted">No hay suscripciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
