@extends('layouts.app')

@section('title', 'Roles')

@section('content')
    <div class="section-heading">
        <div>
            <h2>Roles</h2>
            <p>Administra los perfiles de acceso del sistema.</p>
        </div>
        @if (Auth::user()->rol?->nombre === 'Administrador')
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Nuevo rol
            </a>
        @endif
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
                        <th>Descripción</th>
                        <th>Usuarios</th>
                        @if (Auth::user()->rol?->nombre === 'Administrador')
                            <th class="text-end">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $rol)
                        <tr>
                            <td>#{{ $rol->id }}</td>
                            <td class="fw-semibold">{{ $rol->nombre }}</td>
                            <td>{{ $rol->descripcion ?? 'Sin descripción' }}</td>
                            <td>
                                <span class="badge text-bg-secondary">{{ $rol->usuarios_count ?? 0 }}</span>
                            </td>
                            @if (Auth::user()->rol?->nombre === 'Administrador')
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('roles.show', $rol) }}" class="btn btn-outline-secondary">Ver</a>
                                        <a href="{{ route('roles.edit', $rol) }}" class="btn btn-outline-primary">Editar</a>
                                        <form method="POST" action="{{ route('roles.destroy', $rol) }}" onsubmit="return confirm('¿Deseas eliminar este rol?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
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
