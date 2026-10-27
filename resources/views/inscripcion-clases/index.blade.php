@extends('layouts.app')

@section('title', 'Inscripciones a Clases')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-white mb-0">Inscripciones a Clases</h3>
    <a href="{{ route('inscripciones.create') }}" class="btn btn-primary">Nueva Inscripción</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Socio</th>
                        <th>Clase</th>
                        <th>Horario</th>
                        <th>Fecha de Inscripción</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inscripciones as $inscripcion)
                        <tr>
                            <td class="ps-4 fw-semibold text-white">
                                {{ $inscripcion->socio->nombre ?? 'N/A' }} {{ $inscripcion->socio->apellidos ?? $inscripcion->socio->apellido ?? '' }}
                            </td>
                            <td>{{ $inscripcion->clase->nombre ?? 'N/A' }}</td>
                            <td>
                                {{ $inscripcion->clase->dia_semana ?? '' }} 
                                ({{ is_object($inscripcion->clase->hora_inicio ?? null) ? $inscripcion->clase->hora_inicio->format('H:i') : substr($inscripcion->clase->hora_inicio ?? '', 0, 5) }} - {{ is_object($inscripcion->clase->hora_fin ?? null) ? $inscripcion->clase->hora_fin->format('H:i') : substr($inscripcion->clase->hora_fin ?? '', 0, 5) }})
                            </td>
                            <td>{{ substr($inscripcion->fecha_inscripcion, 0, 10) }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('inscripciones.destroy', $inscripcion->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Deseas dar de baja esta inscripción?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Dar de baja</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay inscripciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection