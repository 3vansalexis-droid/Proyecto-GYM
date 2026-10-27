@extends('layouts.app')

@section('title', 'Detalle del entrenador')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <h2 class="h4 mb-4">{{ $entrenador->nombre }} {{ $entrenador->apellidos }}</h2>
            <dl class="row mb-0">
                <dt class="col-sm-4">Especialidad</dt>
                <dd class="col-sm-8">{{ $entrenador->especialidad ?? '—' }}</dd>
                <dt class="col-sm-4">Teléfono</dt>
                <dd class="col-sm-8">{{ $entrenador->telefono ?? '—' }}</dd>
                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8">{{ $entrenador->activo ? 'Activo' : 'Inactivo' }}</dd>
            </dl>
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('entrenadores.index') }}" class="btn btn-outline-secondary">Volver</a>
            </div>
        </div>
    </div>
@endsection
