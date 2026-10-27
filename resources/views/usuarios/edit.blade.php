@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
    <div class="card mx-auto" style="max-width: 800px;">
        <div class="card-header">Editar usuario</div>
        <div class="card-body p-4">
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
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
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
@endsection
