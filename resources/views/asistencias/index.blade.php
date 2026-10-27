@extends('layouts.app')

@section('title', 'Asistencias')

@section('content')
    <div class="section-heading">
        <div>
            <h2>Asistencias</h2>
            <p>Historial de entradas registradas en recepción.</p>
        </div>
        <a href="{{ route('asistencias.create') }}" class="btn btn-primary">
            <i class="bi bi-upc-scan me-1"></i>Ir a Recepción (Check-in)
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
                        <th>Atendido por</th>
                        <th>Fecha y hora</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($asistencias as $asistencia)
                        <tr>
                            <td>#{{ $asistencia->id }}</td>
                            <td class="fw-semibold">{{ $asistencia->socio?->nombre }} {{ $asistencia->socio?->apellidos }}</td>
                            <td>{{ $asistencia->usuario?->nombre ?? '—' }}</td>
                            <td>{{ optional($asistencia->fecha_hora)->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('asistencias.show', $asistencia) }}" class="btn btn-outline-secondary">Ver</a>
                                    @if (Auth::user()->rol?->nombre === 'Administrador')
                                        <a href="{{ route('asistencias.edit', $asistencia) }}" class="btn btn-outline-primary">Editar</a>
                                        <form action="{{ route('asistencias.destroy', $asistencia) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar esta asistencia?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No hay asistencias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $asistencias->links() }}</div>
@endsection
