@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card bg-dark text-light border-secondary">
                <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detalle del Plan</h4>
                    <span class="badge {{ $tipoMembresia->activo ? 'bg-success' : 'bg-secondary' }}">
                        {{ $tipoMembresia->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-dark text-light border-secondary">
                            <strong>Nombre:</strong> {{ $tipoMembresia->nombre }}
                        </li>
                        <li class="list-group-item bg-dark text-light border-secondary">
                            <strong>Duración:</strong> {{ $tipoMembresia->duracion_dias }} días
                        </li>
                        <li class="list-group-item bg-dark text-light border-secondary">
                            <strong>Precio:</strong> ${{ number_format($tipoMembresia->precio, 2) }}
                        </li>
                    </ul>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('tipos-membresia.index') }}" class="btn btn-secondary">Regresar</a>
                        <a href="{{ route('tipos-membresia.edit', $tipoMembresia) }}" class="btn btn-warning">Editar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection