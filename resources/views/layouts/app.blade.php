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
        <div class="d-flex min-vh-100">
            <aside class="sidebar d-flex flex-column flex-shrink-0 p-3 border-end" style="width: 280px;">
                <a href="{{ url('/') }}" class="d-flex align-items-center mb-4 text-decoration-none link-light">
                    <i class="bi bi-heart-pulse-fill fs-3 me-2"></i>
                    <span class="fs-4 fw-semibold">Gym System</span>
                </a>

                <nav class="nav nav-pills flex-column gap-2" aria-label="Navegación principal">
                    <a href="{{ url('/socios') }}" class="nav-link link-light">
                        <i class="bi bi-people-fill me-2"></i> Socios
                    </a>
                    <a href="{{ url('/asistencias') }}" class="nav-link link-light">
                        <i class="bi bi-calendar-check me-2"></i> Asistencias
                    </a>
                    <a href="{{ url('/suscripciones') }}" class="nav-link link-light">
                        <i class="bi bi-card-checklist me-2"></i> Suscripciones
                    </a>
                    <a href="{{ url('/pagos') }}" class="nav-link link-light">
                        <i class="bi bi-credit-card me-2"></i> Pagos
                    </a>
                    <a href="{{ url('/clases') }}" class="nav-link link-light">
                        <i class="bi bi-activity me-2"></i> Clases
                    </a>
                </nav>
            </aside>

            <main class="flex-grow-1">
                <header class="border-bottom px-4 py-3">
                    <h1 class="h4 mb-0">@yield('title', 'Panel de control')</h1>
                </header>

                <div class="container-fluid p-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </body>
</html>
