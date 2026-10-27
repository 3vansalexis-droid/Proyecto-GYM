@extends('layouts.app')

@section('title', 'Nuevo rol')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-1">Nuevo rol</h2>
                    <p class="text-body-secondary mb-0">Define un rol para el sistema.</p>
                </div>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-light">Volver</a>
            </div>

            <div class="card bg-dark border-secondary shadow-sm">
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                value="{{ old('nombre') }}"
                                class="form-control @error('nombre') is-invalid @enderror"
                                required
                                autofocus
                            >
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea
                                id="descripcion"
                                name="descripcion"
                                rows="4"
                                class="form-control @error('descripcion') is-invalid @enderror"
                            >{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>Guardar rol
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
