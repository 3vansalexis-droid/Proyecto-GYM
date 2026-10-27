@extends('layouts.app')

@section('title', isset($entrenador->id) ? 'Editar entrenador' : 'Nuevo entrenador')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 900px;">
        <div class="card-body p-4">
            <form method="POST" action="{{ isset($entrenador->id) ? route('entrenadores.update', $entrenador) : route('entrenadores.store') }}">
                @csrf
                @if (isset($entrenador->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $entrenador->nombre ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', $entrenador->apellidos ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $entrenador->telefono ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Especialidad</label>
                        <input type="text" name="especialidad" class="form-control" value="{{ old('especialidad', $entrenador->especialidad ?? '') }}">
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="activo" value="1" {{ old('activo', $entrenador->activo ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label">Entrenador activo</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('entrenadores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
