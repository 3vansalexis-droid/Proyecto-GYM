@extends('layouts.app')

@section('title', 'Nuevo plan de membresía')

@section('content')
    <div class="card mx-auto" style="max-width: 720px;">
        <div class="card-header">Registrar tipo de membresía</div>
        <div class="card-body p-4">
            <form action="{{ route('tipos-membresia.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label for="nombre" class="form-label">Nombre del plan</label>
                        <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Ej. Mensualidad Básica" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="duracion_dias" class="form-label">Duración (en días)</label>
                        <input type="number" name="duracion_dias" id="duracion_dias" class="form-control @error('duracion_dias') is-invalid @enderror" value="{{ old('duracion_dias', 30) }}" min="1" required>
                        @error('duracion_dias')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="precio" class="form-label">Precio ($)</label>
                        <input type="number" step="0.01" name="precio" id="precio" class="form-control @error('precio') is-invalid @enderror" value="{{ old('precio') }}" placeholder="0.00" min="0" required>
                        @error('precio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Plan activo para venta</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('tipos-membresia.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar plan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
