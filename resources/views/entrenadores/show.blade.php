@extends('layouts.app')

@section('title', 'Detalle del entrenador')

@section('content')
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0">{{ $entrenador->nombre }} {{ $entrenador->apellidos ?? '' }}</h2>
                <span class="badge text-bg-{{ $entrenador->activo ? 'success' : 'secondary' }}">
                    {{ $entrenador->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>

            <dl class="row mb-0">
                <dt class="col-sm-4">Especialidad</dt>
                <dd class="col-sm-8">{{ $entrenador->especialidad ?? 'Sin asignar' }}</dd>
                <dt class="col-sm-4">Teléfono</dt>
                <dd class="col-sm-8">{{ $entrenador->telefono ?? 'Sin teléfono' }}</dd>
                <dt class="col-sm-4">Clases asignadas</dt>
                <dd class="col-sm-8">{{ $entrenador->clases->count() }}</dd>
            </dl>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('entrenadores.index') }}" class="btn btn-outline-secondary">Volver</a>
                <a href="{{ route('entrenadores.edit', $entrenador->id) }}" class="btn btn-primary">Editar</a>
            </div>
        </div>
    </div>
@endsection
