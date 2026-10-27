@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card bg-dark text-light border-secondary">
                <div class="card-header border-secondary">
                    <h4 class="mb-0">Registrar Tipo de Membresía</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tipos-membresia.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Plan</label>
                            <input type="text" name="nombre" id="nombre" class="form-control bg-dark text-light border-secondary @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Ej. Mensualidad Básica" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="duracion_dias" class="form-label">Duración (en días)</label>
                                <input type="number" name="duracion_dias" id="duracion_dias" class="form-control bg-dark text-light border-secondary @error('duracion_dias') is-invalid @enderror" value="{{ old('duracion_dias', 30) }}" min="1" required>
                                @error('duracion_dias')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="precio" class="form-label">Precio ($)</label>
                                <input type="number" step="0.01" name="precio" id="precio" class="form-control bg-dark text-light border-secondary @error('precio') is-invalid @enderror" value="{{ old('precio') }}" placeholder="0.00" min="0" required>
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Plan activo para venta</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tipos-membresia.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection