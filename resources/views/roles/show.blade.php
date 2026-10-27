@extends('layouts.app')

@section('title', 'Detalle del rol')

@section('content')
    <div class="card bg-dark border-secondary shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <h2 class="h4 mb-4">{{ $rol->nombre }}</h2>
            <dl class="row mb-4">
                <dt class="col-sm-4">Descripción</dt>
                <dd class="col-sm-8">{{ $rol->descripcion ?? 'Sin descripción' }}</dd>
                <dt class="col-sm-4">Usuarios asignados</dt>
                <dd class="col-sm-8">{{ $rol->usuarios->count() }}</dd>
            </dl>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Volver</a>
                <a href="{{ route('roles.edit', $rol) }}" class="btn btn-warning">Editar</a>
            </div>
        </div>
    </div>
@endsection
