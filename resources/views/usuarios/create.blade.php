@extends('layouts.app')

@section('title', 'Nuevo usuario')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-1">Nuevo usuario</h2>
                    <p class="text-body-secondary mb-0">Crea una cuenta para el personal del gimnasio.</p>
                </div>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-light">Volver</a>
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

                    <form method="POST" action="{{ route('usuarios.store') }}">
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

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    minlength="8"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar password</label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control"
                                    minlength="8"
                                    required
                                >
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="rol_id" class="form-label">Rol</label>
                                <select id="rol_id" name="rol_id" class="form-select @error('rol_id') is-invalid @enderror" required>
                                    <option value="">Selecciona un rol</option>
                                    @foreach ($roles as $id => $nombre)
                                        <option value="{{ $id }}" @selected((string) old('rol_id') === (string) $id)>
                                            {{ $nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rol_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="activo" class="form-label">Estado</label>
                                <select id="activo" name="activo" class="form-select @error('activo') is-invalid @enderror" required>
                                    <option value="1" @selected(old('activo', '1') === '1')>Activo</option>
                                    <option value="0" @selected(old('activo') === '0')>Inactivo</option>
                                </select>
                                @error('activo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>Guardar usuario
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
