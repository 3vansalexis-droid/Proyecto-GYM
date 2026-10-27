@extends('layouts.app')

@section('title', 'Detalle de asistencia')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <h2 class="h4 mb-4">Asistencia #{{ $asistencia->id }}</h2>
            <dl class="row mb-0">
                <dt class="col-sm-4">Socio</dt>
                <dd class="col-sm-8">{{ $asistencia->socio?->nombre }} {{ $asistencia->socio?->apellidos }}</dd>
                <dt class="col-sm-4">Usuario</dt>
                <dd class="col-sm-8">{{ $asistencia->usuario?->nombre ?? '—' }}</dd>
                <dt class="col-sm-4">Fecha y hora</dt>
                <dd class="col-sm-8">{{ optional($asistencia->fecha_hora)->format('d/m/Y H:i') }}</dd>
            </dl>
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('asistencias.index') }}" class="btn btn-outline-secondary">Volver</a>
            </div>
        </div>
    </div>
@endsection
