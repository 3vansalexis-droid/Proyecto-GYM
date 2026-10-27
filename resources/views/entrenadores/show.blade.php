@extends('layouts.app')

@section('title', 'Detalle del entrenador')

@section('content')
<div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-white">{{ $entrenador->nombre }} {{ $entrenador->apellido ?? '' }}</h4>
            <span class="badge {{ $entrenador->estado === 'activo' || $entrenador->activo ? 'bg-success' : 'bg-secondary' }}">
                {{ ucfirst($entrenador->estado ?? ($entrenador->activo ? 'Activo' : 'Inactivo')) }}
            </span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <p class="text-muted mb-1">Especialidad</p>
                <h6 class="text-white">{{ $entrenador->especialidad ?? 'Sin asignar' }}</h6>
            </div>

            <div class="col-md-6">
                <p class="text-muted mb-1">Teléfono</p>
                <h6 class="text-white">{{ $entrenador->telefono ?? 'Sin teléfono' }}</h6>
            </div>

            @if(isset($entrenador->email) || isset($entrenador->correo))
                <div class="col-md-6">
                    <p class="text-muted mb-1">Correo Electrónico</p>
                    <h6 class="text-white">{{ $entrenador->email ?? $entrenador->correo }}</h6>
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('entrenadores.index') }}" class="btn btn-outline-secondary">Volver</a>
            <a href="{{ route('entrenadores.edit', $entrenador->id) }}" class="btn btn-warning">Editar</a>
        </div>
    </div>
</div>
@endsection