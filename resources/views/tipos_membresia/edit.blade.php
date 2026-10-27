@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card bg-dark text-light border-secondary">
                <div class="card-header border-secondary">
                    <h4 class="mb-0">Editar Tipo de Membresía</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tipos-membresia.update', $tipoMembresia) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Plan</label>
                            <input type="text" name="nombre" id="nombre" class="form-control bg-dark text-light border-secondary @error('nombre') is-invalid @enderror" value="{{ old('nombre', $tipoMembresia->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="duracion_dias" class="form-label">Duración (en días)</label>
                                <input type="number" name="duracion_dias" id="duracion_dias" class="form-control bg-dark text-light border-secondary @error('duracion_dias') is-invalid @enderror" value="{{ old('duracion_dias', $tipoMembresia->duracion_dias) }}" min="1" required>
                                @error('duracion_dias')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="precio" class="form-label">Precio ($)</label>
                                <input type="number" step="0.01" name="precio" id="precio" class="form-control bg-dark text-light border-secondary @error('precio') is-invalid @enderror" value="{{ old('precio', $tipoMembresia->precio) }}" min="0" required>
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" value="1" {{ old('activo', $tipoMembresia->activo) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Plan activo para venta</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tipos-membresia.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection