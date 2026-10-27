@extends('layouts.app')

@section('title', 'Detalle del plan')

@section('content')
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0">{{ $tipoMembresia->nombre }}</h2>
                <span class="badge text-bg-{{ $tipoMembresia->activo ? 'success' : 'secondary' }}">
                    {{ $tipoMembresia->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>

            <dl class="row mb-0">
                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">{{ $tipoMembresia->nombre }}</dd>
                <dt class="col-sm-4">Duración</dt>
                <dd class="col-sm-8">{{ $tipoMembresia->duracion_dias }} días</dd>
                <dt class="col-sm-4">Precio</dt>
                <dd class="col-sm-8">${{ number_format($tipoMembresia->precio, 2) }}</dd>
            </dl>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('tipos-membresia.index') }}" class="btn btn-outline-secondary">Volver</a>
                <a href="{{ route('tipos-membresia.edit', $tipoMembresia) }}" class="btn btn-primary">Editar</a>
            </div>
        </div>
    </div>
@endsection
