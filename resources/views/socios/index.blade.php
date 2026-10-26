@extends('layouts.app')

@section('title', 'Socios')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">Socios</h2>
            <p class="text-body-secondary mb-0">Administra los socios del gimnasio.</p>
        </div>
        <a href="{{ route('socios.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i>Nuevo socio
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
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($socios as $socio)
                        <tr>
                            <td class="fw-semibold">{{ $socio->clave_socio }}</td>
                            <td>{{ $socio->nombre }} {{ $socio->apellidos }}</td>
                            <td>{{ $socio->email ?? $socio->telefono ?? 'Sin datos' }}</td>
                            <td>
                                <span class="badge text-bg-{{ $socio->activo ? 'success' : 'secondary' }}">
                                    {{ $socio->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('socios.show', $socio) }}" class="btn btn-sm btn-outline-light">Ver</a>
                                <a href="{{ route('socios.edit', $socio) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form method="POST" action="{{ route('socios.destroy', $socio) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-body-secondary py-4">No hay socios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $socios->links() }}</div>
@endsection
