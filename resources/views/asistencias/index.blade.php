@extends('layouts.app')

@section('title', 'Asistencias')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Asistencias</h2>
        <a href="{{ route('asistencias.create') }}" class="btn btn-primary">Registrar asistencia</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Socio</th>
                        <th>Usuario</th>
                        <th>Fecha y hora</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($asistencias as $asistencia)
                        <tr>
                            <td>{{ $asistencia->id }}</td>
                            <td>{{ $asistencia->socio?->nombre }} {{ $asistencia->socio?->apellidos }}</td>
                            <td>{{ $asistencia->usuario?->nombre ?? '—' }}</td>
                            <td>{{ optional($asistencia->fecha_hora)->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('asistencias.show', $asistencia) }}" class="btn btn-outline-secondary">Ver</a>
                                    <a href="{{ route('asistencias.edit', $asistencia) }}" class="btn btn-outline-primary">Editar</a>
                                    <form action="{{ route('asistencias.destroy', $asistencia) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar esta asistencia?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                    </form>
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
@endsection
