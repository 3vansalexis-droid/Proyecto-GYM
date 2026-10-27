@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-light mb-0">Tipos de Membresía</h2>
        <a href="{{ route('tipos-membresia.create') }}" class="btn btn-primary">
            + Nuevo Plan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card bg-dark text-light border-secondary">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="table-secondary text-uppercase fs-7">
                        <tr>
                            <th scope="col" class="ps-3">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Duración</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tiposMembresia as $tipo)
                            <tr>
                                <td class="ps-3">{{ $tipo->id }}</td>
                                <td class="fw-bold">{{ $tipo->nombre }}</td>
                                <td>{{ $tipo->duracion_dias }} días</td>
                                <td>${{ number_format($tipo->precio, 2) }}</td>
                                <td>
                                    @if($tipo->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('tipos-membresia.show', $tipo) }}" class="btn btn-sm btn-outline-info me-1">
                                        Ver
                                    </a>
                                    <a href="{{ route('tipos-membresia.edit', $tipo) }}" class="btn btn-sm btn-outline-warning me-1">
                                        Editar
                                    </a>
                                    <form action="{{ route('tipos-membresia.destroy', $tipo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este plan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Eliminar
                                        </button>
                                    </form>
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
    </div>

    <div class="mt-3">
        {{ $tiposMembresia->links() }}
    </div>
</div>
@endsection