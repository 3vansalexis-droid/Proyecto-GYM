@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <div class="section-heading">
        <div>
            <h2>Usuarios</h2>
            <p>Administra el personal con acceso al sistema.</p>
        </div>
        @if (Auth::user()->rol?->nombre === 'Administrador')
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus-fill me-1"></i>Nuevo usuario
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
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        @if (Auth::user()->rol?->nombre === 'Administrador')
                            <th class="text-end">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>#{{ $usuario->id }}</td>
                            <td class="fw-semibold">{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="badge text-bg-secondary">{{ $usuario->rol?->nombre ?? 'Sin rol' }}</span>
                            </td>
                            <td>
                                <span class="badge text-bg-{{ $usuario->activo ? 'success' : 'secondary' }}">
                                    {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            @if (Auth::user()->rol?->nombre === 'Administrador')
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-outline-secondary">Ver</a>
                                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-outline-primary">Editar</a>
                                        @if (Auth::id() !== $usuario->id)
                                            <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" onsubmit="return confirm('¿Deseas eliminar a este usuario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (method_exists($usuarios, 'links'))
        <div class="mt-4">{{ $usuarios->links() }}</div>
    @endif
@endsection
