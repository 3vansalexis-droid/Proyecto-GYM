@extends('layouts.app')

@section('title', 'Entrenadores')

@section('content')
    <div class="section-heading">
        <div>
            <h2>Entrenadores</h2>
            <p>Administra los instructores y sus especialidades.</p>
        </div>
        <a href="{{ route('entrenadores.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i>Nuevo entrenador
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
                            <td>#{{ $entrenador->id }}</td>
                            <td class="fw-semibold">{{ $entrenador->nombre }} {{ $entrenador->apellidos }}</td>
                            <td>{{ $entrenador->especialidad ?? '—' }}</td>
                            <td>{{ $entrenador->telefono ?? '—' }}</td>
                            <td>
                                <span class="badge text-bg-{{ $entrenador->activo ? 'success' : 'secondary' }}">
                                    {{ $entrenador->activo ? 'Activo' : 'Inactivo' }}
                                </span>
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
