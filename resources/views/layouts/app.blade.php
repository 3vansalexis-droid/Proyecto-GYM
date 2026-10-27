<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Panel de control') | {{ config('app.name', 'Gym System') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        @php($rol = Auth::user()->rol?->nombre)
        <div class="app-shell">
            <aside class="app-sidebar">
                <a class="brand" href="{{ route('dashboard') }}">
                    <span class="brand-mark"><i class="bi bi-heart-pulse-fill"></i></span>
                    <span>Gym System</span>
                </a>

                <div class="sidebar-label">General</div>
                <nav class="sidebar-nav" aria-label="Navegación principal">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                    </a>

                    @if (in_array($rol, ['Administrador', 'Recepcionista'], true))
                        <div class="sidebar-label px-2 pt-3 pb-1">Operación</div>
                        <a class="nav-link {{ request()->routeIs('asistencias.create') ? 'active' : '' }}" href="{{ route('asistencias.create') }}">
                            <i class="bi bi-upc-scan"></i><span>Recepción (Check-in)</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('asistencias.*') && ! request()->routeIs('asistencias.create') ? 'active' : '' }}" href="{{ route('asistencias.index') }}">
                            <i class="bi bi-calendar-check"></i><span>Asistencias</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('socios.*') ? 'active' : '' }}" href="{{ route('socios.index') }}">
                            <i class="bi bi-people-fill"></i><span>Socios</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('suscripciones.*') ? 'active' : '' }}" href="{{ route('suscripciones.index') }}">
                            <i class="bi bi-calendar-plus"></i><span>Suscripciones</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}" href="{{ route('pagos.index') }}">
                            <i class="bi bi-cash-coin"></i><span>Pagos</span>
                        </a>
                    @endif

                    @if (in_array($rol, ['Administrador', 'Entrenador'], true))
                        <a class="nav-link {{ request()->routeIs('clases.*') ? 'active' : '' }}" href="{{ route('clases.index') }}">
                            <i class="bi bi-calendar-event"></i><span>Clases</span>
                        </a>
                    @endif

                    @if ($rol === 'Administrador')
                        <div class="sidebar-label px-2 pt-3 pb-1">Administración</div>
                        <a class="nav-link {{ request()->routeIs('tipos-membresia.*') ? 'active' : '' }}" href="{{ route('tipos-membresia.index') }}">
                            <i class="bi bi-card-checklist"></i><span>Membresías</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('entrenadores.*') ? 'active' : '' }}" href="{{ route('entrenadores.index') }}">
                            <i class="bi bi-person-workspace"></i><span>Entrenadores</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('inscripciones.*') ? 'active' : '' }}" href="{{ route('inscripciones.index') }}">
                            <i class="bi bi-person-plus"></i><span>Inscripciones</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                            <i class="bi bi-person-badge-fill"></i><span>Usuarios</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                            <i class="bi bi-shield-lock-fill"></i><span>Roles</span>
                        </a>
                    @endif
                </nav>

                <div class="sidebar-footer">
                    <div class="user-chip">
                        <span class="user-avatar"><i class="bi bi-person-fill"></i></span>
                        <span class="text-truncate">{{ Auth::user()->nombre }}<small>{{ $rol }}</small></span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100 text-white border-secondary">
                            <i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión
                        </button>
                    </form>
                </div>
            </aside>

            <div class="app-main">
                <header class="app-topbar">
                    <h1 class="page-heading">@yield('title', 'Panel de control')</h1>
                    <span class="text-body-secondary d-none d-sm-inline">
                        <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d \d\e F \d\e Y') }}
                    </span>
                </header>
                <main class="page-content">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
