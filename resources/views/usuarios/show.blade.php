@extends('layouts.app')

@section('title', 'Detalle del usuario')

@section('content')
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0">{{ $usuario->nombre }}</h2>
                <span class="badge text-bg-{{ $usuario->activo ? 'success' : 'secondary' }}">
                    {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            <dl class="row mb-0">
                <dt class="col-sm-4">Correo</dt>
                <dd class="col-sm-8">{{ $usuario->email }}</dd>
                <dt class="col-sm-4">Rol</dt>
                <dd class="col-sm-8">{{ $usuario->rol?->nombre ?? 'Sin rol' }}</dd>
                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</dd>
            </dl>
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Volver</a>
                @if (Auth::user()->rol?->nombre === 'Administrador')
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-primary">Editar</a>
                    @if (Auth::id() !== $usuario->id)
                        <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" onsubmit="return confirm('¿Deseas eliminar a este usuario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
