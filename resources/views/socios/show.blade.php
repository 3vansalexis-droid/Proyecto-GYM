@extends('layouts.app')

@section('title', 'Detalle del socio')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">{{ $socio->nombre }} {{ $socio->apellidos }}</h2>
            <p class="text-body-secondary mb-0">Detalle del socio {{ $socio->clave_socio }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('socios.edit', $socio) }}" class="btn btn-primary">Editar</a>
            <a href="{{ route('socios.index') }}" class="btn btn-outline-light">Volver</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Clave</dt><dd class="col-sm-8">{{ $socio->clave_socio }}</dd>
                        <dt class="col-sm-4">Nombre completo</dt><dd class="col-sm-8">{{ $socio->nombre }} {{ $socio->apellidos }}</dd>
                        <dt class="col-sm-4">Teléfono</dt><dd class="col-sm-8">{{ $socio->telefono ?? 'Sin registrar' }}</dd>
                        <dt class="col-sm-4">Correo</dt><dd class="col-sm-8">{{ $socio->email ?? 'Sin registrar' }}</dd>
                        <dt class="col-sm-4">Nacimiento</dt><dd class="col-sm-8">{{ $socio->fecha_nacimiento?->format('d/m/Y') ?? 'Sin registrar' }}</dd>
                        <dt class="col-sm-4">Estado</dt><dd class="col-sm-8">{{ $socio->activo ? 'Activo' : 'Inactivo' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h5">Registros relacionados</h3>
                    <p class="mb-2">Suscripciones: <strong>{{ $socio->suscripciones_count }}</strong></p>
                    <p class="mb-2">Asistencias: <strong>{{ $socio->asistencias_count }}</strong></p>
                    <p class="mb-0">Clases inscritas: <strong>{{ $socio->inscripcion_clases_count }}</strong></p>
                </div>
            </div>
        </div>
    </div>
@endsection
