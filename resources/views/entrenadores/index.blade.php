@extends('layouts.app')

@section('title', 'Entrenadores')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Entrenadores</h2>
        <a href="{{ route('entrenadores.create') }}" class="btn btn-primary">Nuevo entrenador</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Especialidad</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($entrenadores as $entrenador)
                        <tr>
                            <td>{{ $entrenador->id }}</td>
                            <td>{{ $entrenador->nombre }} {{ $entrenador->apellidos }}</td>
                            <td>{{ $entrenador->especialidad ?? '—' }}</td>
                            <td>{{ $entrenador->telefono ?? '—' }}</td>
                            <td>
                                @if ($entrenador->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('entrenadores.show', $entrenador) }}" class="btn btn-outline-secondary">Ver</a>
                                    <a href="{{ route('entrenadores.edit', $entrenador) }}" class="btn btn-outline-primary">Editar</a>
                                    <form action="{{ route('entrenadores.destroy', $entrenador) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este entrenador?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No hay entrenadores registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
