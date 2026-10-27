@extends('layouts.app')

@section('title', 'Socios')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Socios</h2>
        <a href="{{ route('socios.create') }}" class="btn btn-primary">Nuevo socio</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($socios as $socio)
                        <tr>
                            <td>{{ $socio->id }}</td>
                            <td>{{ $socio->clave_socio }}</td>
                            <td>{{ $socio->nombre }} {{ $socio->apellidos }}</td>
                            <td>{{ $socio->telefono ?? '—' }}</td>
                            <td>{{ $socio->email ?? '—' }}</td>
                            <td>
                                @if ($socio->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('socios.show', $socio) }}" class="btn btn-outline-secondary">Ver</a>
                                    <a href="{{ route('socios.edit', $socio) }}" class="btn btn-outline-primary">Editar</a>
                                    <form action="{{ route('socios.destroy', $socio) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este socio?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No hay socios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
