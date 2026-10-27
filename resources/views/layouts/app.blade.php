<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Gym System') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-dark border-bottom border-secondary" data-bs-theme="dark">
            <div class="container-fluid px-4">
                <a class="navbar-brand fw-semibold" href="{{ url('/') }}">
                    <i class="bi bi-heart-pulse-fill me-2"></i>Gym System
                </a>

                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar"
                    aria-expanded="false"
                    aria-label="Alternar navegación"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        @if (in_array(Auth::user()->rol?->nombre, ['Administrador', 'Recepcionista'], true))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('asistencias.create') }}">
                                    <i class="bi bi-calendar-check me-1"></i>Recepción
                                </a>
                            </li>
                        @endif
                        @if (in_array(Auth::user()->rol?->nombre, ['Administrador', 'Recepcionista'], true))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('socios.index') }}">
                                    <i class="bi bi-people-fill me-1"></i>Socios
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->rol?->nombre === 'Administrador')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('tipos-membresia.index') }}">
                                    <i class="bi bi-card-checklist me-1"></i>Membresías
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('entrenadores.index') }}">
                                    <i class="bi bi-person-workspace me-1"></i>Entrenadores
                                </a>
                            </li>
                        @endif
                        @if (in_array(Auth::user()->rol?->nombre, ['Administrador', 'Recepcionista'], true))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('suscripciones.index') }}">
                                    <i class="bi bi-calendar-plus me-1"></i>Suscripciones
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pagos.index') }}">
                                    <i class="bi bi-cash-coin me-1"></i>Pagos
                                </a>
                            </li>
                        @endif
                        @if (in_array(Auth::user()->rol?->nombre, ['Administrador', 'Entrenador'], true))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('clases.index') }}">
                                    <i class="bi bi-calendar-event me-1"></i>Clases
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->rol?->nombre === 'Administrador')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('inscripciones.index') }}">
                                    <i class="bi bi-person-plus me-1"></i>Inscripciones
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->rol?->nombre === 'Administrador')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('usuarios.index') }}">
                                    <i class="bi bi-person-badge-fill me-1"></i>Usuarios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('roles.index') }}">
                                    <i class="bi bi-shield-lock-fill me-1"></i>Roles
                                </a>
                            </li>
                        @endif
                    </ul>

                    @auth
                        <div class="d-flex align-items-center gap-3">
                            <span class="navbar-text">
                                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->nombre }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm">
                                    <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <main>
            <header class="border-bottom px-4 py-3">
                <h1 class="h4 mb-0">@yield('title', 'Panel de control')</h1>
            </header>

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </main>
    </body>
</html>
