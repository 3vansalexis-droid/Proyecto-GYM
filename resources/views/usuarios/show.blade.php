@extends('layouts.app')

@section('title', 'Detalle del usuario')

@section('content')
    <div class="card bg-dark border-secondary shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <h2 class="h4 mb-4">{{ $usuario->nombre }}</h2>
            <dl class="row mb-4">
                <dt class="col-sm-4">Correo</dt>
                <dd class="col-sm-8">{{ $usuario->email }}</dd>
                <dt class="col-sm-4">Rol</dt>
                <dd class="col-sm-8">{{ $usuario->rol?->nombre ?? 'Sin rol' }}</dd>
                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</dd>
            </dl>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Volver</a>
                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning">Editar</a>
            </div>
        </div>
    </div>
@endsection
