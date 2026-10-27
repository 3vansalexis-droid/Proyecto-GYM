<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Iniciar sesión | {{ config('app.name', 'Gym System') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-body-tertiary">
        <main class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
            <div class="w-100" style="max-width: 420px;">
                <div class="text-center mb-4">
                    <i class="bi bi-heart-pulse-fill text-primary" style="font-size: 3rem;"></i>
                    <h1 class="h3 fw-semibold mt-2 mb-1">{{ config('app.name', 'Gym System') }}</h1>
                    <p class="text-body-secondary mb-0">Inicia sesión para continuar</p>
                </div>

                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4 p-sm-5">
                        @if (session('status'))
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <div>{{ session('status') }}</div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.attempt') }}" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="correo@ejemplo.com"
                                        autocomplete="email"
                                        required
                                        autofocus
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="••••••••"
                                        autocomplete="current-password"
                                        required
                                    >
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        id="togglePassword"
                                        aria-label="Mostrar u ocultar contraseña"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-center text-body-secondary small mt-4 mb-0">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Gym System') }}
                </p>
            </div>
        </main>

        <script>
            document.getElementById('togglePassword').addEventListener('click', function () {
                const input = document.getElementById('password');
                const icon = this.querySelector('i');
                const visible = input.type === 'text';

                input.type = visible ? 'password' : 'text';
                icon.classList.toggle('bi-eye', visible);
                icon.classList.toggle('bi-eye-slash', !visible);
            });
        </script>
    </body>
</html>

