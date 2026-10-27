@extends('layouts.app')

@section('title', 'Tipos de membresía')

@section('content')
    <div class="section-heading">
        <div>
            <h2>Tipos de membresía</h2>
            <p>Administra los planes disponibles para tus socios.</p>
        </div>
        <a href="{{ route('tipos-membresia.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nuevo plan
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
                        <th>Duración</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tiposMembresia as $tipo)
                        <tr>
                            <td>#{{ $tipo->id }}</td>
                            <td class="fw-semibold">{{ $tipo->nombre }}</td>
                            <td>{{ $tipo->duracion_dias }} días</td>
                            <td>${{ number_format($tipo->precio, 2) }}</td>
                            <td>
                                <span class="badge text-bg-{{ $tipo->activo ? 'success' : 'secondary' }}">
                                    {{ $tipo->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('tipos-membresia.show', $tipo) }}" class="btn btn-outline-secondary">Ver</a>
                                    <a href="{{ route('tipos-membresia.edit', $tipo) }}" class="btn btn-outline-primary">Editar</a>
                                    <form action="{{ route('tipos-membresia.destroy', $tipo) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este plan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No hay tipos de membresía registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $tiposMembresia->links() }}
    </div>
@endsection
