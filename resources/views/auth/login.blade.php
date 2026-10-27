<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Iniciar sesión | Gym System</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="login-backdrop">
        <main class="container-fluid d-flex align-items-center justify-content-center min-vh-100 p-3 p-md-4">
            <div class="login-showcase" style="--login-hero-bg: url('{{ asset('images/login-hero-pastel.jpg') }}');">
                {{-- Tarjeta flotante izquierda (estilo cristal / frosted) --}}
                <div class="login-floating-card">
                    <div>
                        {{-- Logo estilo "e | FITNESS / Gym management" adaptado a Gym System --}}
                        <div class="login-brand-lockup">
                            <span class="login-brand-icon">
                                <i class="bi bi-heart-pulse-fill"></i>
                            </span>
                            <span class="login-brand-divider"></span>
                            <div class="text-start">
                                <span class="login-brand-title">GYM SYSTEM</span>
                                <small class="login-brand-subtitle">Control de gimnasio</small>
                            </div>
                        </div>

                        <h1 class="login-heading">Iniciar sesión</h1>

                        @if (session('status'))
                            <div class="alert alert-success py-2 small text-center mb-3" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.attempt') }}" novalidate>
                            @csrf

                            <div class="mb-3">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control login-input @error('email') is-invalid @enderror"
                                    placeholder="Correo electrónico"
                                    aria-label="Correo electrónico"
                                    autocomplete="email"
                                    required
                                    autofocus
                                >
                                @error('email')
                                    <div class="invalid-feedback text-start">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="position-relative">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control login-input pe-5 @error('password') is-invalid @enderror"
                                        placeholder="Contraseña"
                                        aria-label="Contraseña"
                                        autocomplete="current-password"
                                        required
                                    >
                                    <button
                                        type="button"
                                        class="login-eye-btn"
                                        id="togglePassword"
                                        aria-label="Mostrar u ocultar contraseña"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback text-start">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center small text-body-secondary mb-4 px-1">
                                <span><i class="bi bi-shield-lock text-primary me-1"></i>Sesión segura</span>
                                <span>Personal autorizado</span>
                            </div>

                            <button type="submit" class="btn login-submit-btn w-100">
                                Iniciar sesión
                            </button>
                        </form>
                    </div>

                    <div class="login-card-footer">
                        Recepción &nbsp;|&nbsp; Membresías &nbsp;|&nbsp; Clases
                    </div>
                </div>

                {{-- Escenario visual derecho --}}
                <div class="login-stage-caption d-none d-lg-flex">
                    <span class="badge rounded-pill px-3 py-2">
                        <i class="bi bi-lightning-charge-fill me-1"></i> Sistema de Control y Membresías
                    </span>
                </div>
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
