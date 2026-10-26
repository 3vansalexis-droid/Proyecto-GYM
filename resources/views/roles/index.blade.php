@extends('layouts.app')

@section('title', 'Roles')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">Roles</h2>
            <p class="text-body-secondary mb-0">Administra los roles del sistema.</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i>Nuevo Rol
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $rol)
                        <tr>
                            <td>{{ $rol->id }}</td>
                            <td class="fw-semibold">{{ $rol->nombre }}</td>
                            <td>{{ $rol->descripcion ?? 'Sin descripción' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-body-secondary py-4">
                                No hay roles registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (method_exists($roles, 'links'))
        <div class="mt-4">{{ $roles->links() }}</div>
    @endif
@endsection
