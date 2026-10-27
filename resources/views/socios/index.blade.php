@extends('layouts.app')

@section('title', 'Socios')

@section('content')
    <div class="section-heading">
        <div>
            <h2>Socios</h2>
            <p>Administra los socios registrados en el gimnasio.</p>
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

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('socios.show', $socio) }}" class="btn btn-outline-secondary">Ver</a>
                                    <a href="{{ route('socios.edit', $socio) }}" class="btn btn-outline-primary">Editar</a>
                                    <form method="POST" action="{{ route('socios.destroy', $socio) }}" onsubmit="return confirm('¿Deseas eliminar este socio?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay socios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $socios->links() }}</div>
@endsection
