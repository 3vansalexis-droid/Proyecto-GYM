@extends('layouts.app')

@section('title', 'Inscripciones a clases')

@section('content')
    <div class="section-heading">
        <div>
            <h2>Inscripciones a clases</h2>
            <p>Administra los socios inscritos en cada clase grupal.</p>
        </div>
        <a href="{{ route('inscripciones.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i>Nueva inscripción
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
                        <th>Socio</th>
                        <th>Clase</th>
                        <th>Horario</th>
                        <th>Fecha de inscripción</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inscripciones as $inscripcion)
                        <tr>
                            <td>#{{ $inscripcion->id }}</td>
                            <td class="fw-semibold">
                                {{ $inscripcion->socio->nombre ?? 'N/A' }} {{ $inscripcion->socio->apellidos ?? '' }}
                            </td>
                            <td>{{ $inscripcion->clase->nombre ?? 'N/A' }}</td>
                            <td>
                                {{ $inscripcion->clase->dia_semana ?? '' }}
                                ({{ optional($inscripcion->clase?->hora_inicio)->format('H:i') }} - {{ optional($inscripcion->clase?->hora_fin)->format('H:i') }})
                            </td>
                            <td>{{ optional($inscripcion->fecha_inscripcion)->format('d/m/Y') ?? substr((string) $inscripcion->fecha_inscripcion, 0, 10) }}</td>
                            <td class="text-end">
                                <form action="{{ route('inscripciones.destroy', $inscripcion->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Deseas dar de baja esta inscripción?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Dar de baja</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay inscripciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
