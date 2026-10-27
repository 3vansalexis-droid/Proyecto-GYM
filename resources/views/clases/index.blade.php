@extends('layouts.app')

@section('title', 'Clases')

@section('content')
    <div class="section-heading">
        <div>
            <h2>Clases</h2>
            <p>Consulta y administra los horarios, entrenadores y cupos de cada sesión.</p>
        </div>
        <a href="{{ route('clases.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nueva clase
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
                        <th>Entrenador</th>
                        <th>Día</th>
                        <th>Horario</th>
                        <th>Ocupación</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clases as $clase)
                        <tr>
                            <td>#{{ $clase->id }}</td>
                            <td class="fw-semibold">{{ $clase->nombre }}</td>
                            <td>{{ $clase->entrenador?->nombre }} {{ $clase->entrenador?->apellidos }}</td>
                            <td><span class="badge text-bg-secondary">{{ $clase->dia_semana }}</span></td>
                            <td>{{ optional($clase->hora_inicio)->format('H:i') }} - {{ optional($clase->hora_fin)->format('H:i') }}</td>
                            <td>
                                @php($inscritos = $clase->inscripciones_count ?? 0)
                                <span class="badge text-bg-{{ $inscritos >= $clase->cupo_maximo ? 'danger' : 'success' }}">
                                    {{ $inscritos }} / {{ $clase->cupo_maximo }}
                                </span>
                            </td>
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
