@extends('layouts.app')

@section('title', 'Clases')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Clases</h2>
        <a href="{{ route('clases.create') }}" class="btn btn-primary">Nueva clase</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Entrenador</th>
                        <th>Día</th>
                        <th>Horario</th>
                        <th>Cupo</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clases as $clase)
                        <tr>
                            <td>{{ $clase->id }}</td>
                            <td>{{ $clase->nombre }}</td>
                            <td>{{ $clase->entrenador?->nombre }} {{ $clase->entrenador?->apellidos }}</td>
                            <td>{{ $clase->dia_semana }}</td>
                            <td>{{ $clase->hora_inicio }} - {{ $clase->hora_fin }}</td>
                            <td>{{ $clase->cupo_maximo }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('clases.show', $clase) }}" class="btn btn-outline-secondary">Ver</a>
                                    <a href="{{ route('clases.edit', $clase) }}" class="btn btn-outline-primary">Editar</a>
                                    <form action="{{ route('clases.destroy', $clase) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar esta clase?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No hay clases registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
