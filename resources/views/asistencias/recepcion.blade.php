@extends('layouts.app')

@section('title', 'Recepción / Check-in')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-75">
        <div class="w-100" style="max-width: 640px;">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5 text-center">
                    <i class="bi bi-calendar-check text-primary" style="font-size: 4rem;"></i>
                    <h2 class="h3 mt-3 mb-2">Recepción / Check-in</h2>
                    <p class="text-body-secondary mb-4">
                        Escanea o ingresa la clave del socio para registrar su asistencia.
                    </p>

                    @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center text-start" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-center text-start" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    @error('clave_socio')
                        <div class="alert alert-danger text-start" role="alert">{{ $message }}</div>
                    @enderror

                    <form method="POST" action="{{ route('asistencias.store') }}">
                        @csrf

                        <div class="mb-4 text-start">
                            <label for="clave_socio" class="form-label">Clave del socio</label>
                            <input
                                type="text"
                                id="clave_socio"
                                name="clave_socio"
                                value="{{ old('clave_socio') }}"
                                class="form-control form-control-lg text-center @error('clave_socio') is-invalid @enderror"
                                placeholder="Escanea la clave"
                                autocomplete="off"
                                autofocus
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-check2-circle me-2"></i>Registrar entrada
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
