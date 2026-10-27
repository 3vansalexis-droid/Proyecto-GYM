@extends('layouts.app')

@section('title', isset($socio->id) ? 'Editar socio' : 'Nuevo socio')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 900px;">
        <div class="card-body p-4">
            <form method="POST" action="{{ isset($socio->id) ? route('socios.update', $socio) : route('socios.store') }}">
                @csrf
                @if (isset($socio->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Clave del socio</label>
                        <input type="text" name="clave_socio" class="form-control" value="{{ old('clave_socio', $socio->clave_socio ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $socio->email ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $socio->nombre ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', $socio->apellidos ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $socio->telefono ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', optional($socio->fecha_nacimiento ?? null)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha de registro</label>
                        <input type="date" name="fecha_registro" class="form-control" value="{{ old('fecha_registro', optional($socio->fecha_registro ?? null)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="activo" value="1" {{ old('activo', $socio->activo ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label">Socio activo</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('socios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
