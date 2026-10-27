@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Editar usuario</h2>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-light">Volver</a>
            </div>
            <div class="card bg-dark border-secondary shadow-sm">
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" class="form-control" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="rol_id" class="form-label">Rol</label>
                                <select id="rol_id" name="rol_id" class="form-select" required>
                                    @foreach ($roles as $id => $nombre)
                                        <option value="{{ $id }}" @selected(old('rol_id', $usuario->rol_id) == $id)>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="activo" class="form-label">Estado</label>
                                <select id="activo" name="activo" class="form-select">
                                    <option value="1" @selected(old('activo', $usuario->activo ? '1' : '0') === '1')>Activo</option>
                                    <option value="0" @selected(old('activo', $usuario->activo ? '1' : '0') === '0')>Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Nueva contraseña (opcional)</label>
                                <input type="password" id="password" name="password" class="form-control" minlength="8">
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" minlength="8">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
