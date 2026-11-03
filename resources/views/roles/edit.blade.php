@extends('layouts.app')

@section('title', 'Editar rol')

@section('content')
    @php
        $catalogo = $catalogoPermisos ?? \App\Models\Rol::CATALOGO_PERMISOS;
        $seleccionados = old('permisos', $permisosActuales ?? $rol->permisosEfectivos());
        $esAdminRol = mb_strtolower(trim((string) $rol->nombre)) === 'administrador';
    @endphp

    <div class="card mx-auto" style="max-width: 860px;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Editar rol: {{ $rol->nombre }}</span>
            <span class="badge text-bg-light">Control de acceso</span>
        </div>
        <div class="card-body p-4">
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('roles.update', $rol) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label for="nombre" class="form-label">Nombre del rol</label>
                        <input
                            id="nombre"
                            name="nombre"
                            value="{{ old('nombre', $rol->nombre) }}"
                            class="form-control @error('nombre') is-invalid @enderror"
                            @readonly($esAdminRol)
                            required
                        >
                        @if ($esAdminRol)
                            <div class="form-text">El nombre del rol Administrador principal está protegido.</div>
                        @endif
                    </div>

                    <div class="col-md-7">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <input
                            id="descripcion"
                            name="descripcion"
                            value="{{ old('descripcion', $rol->descripcion) }}"
                            class="form-control @error('descripcion') is-invalid @enderror"
                        >
                    </div>
                </div>

                <div class="border-top pt-4 mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h3 class="h6 fw-bold mb-1">Permisos y módulos habilitados</h3>
                            <p class="text-body-secondary small mb-0">
                                @if ($esAdminRol)
                                    El rol <strong>Administrador</strong> conserva siempre todos los permisos del sistema por seguridad.
                                @else
                                    Marca o desmarca las secciones a las que tendrán acceso los usuarios con este rol.
                                @endif
                            </p>
                        </div>
                        @if (! $esAdminRol)
                            <div class="btn-group btn-group-sm" role="group" aria-label="Plantillas rápidas de permisos">
                                <button type="button" class="btn btn-outline-secondary" data-preset="recepcion">
                                    Perfil Recepción
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-preset="entrenador">
                                    Perfil Entrenador
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-preset="todos">
                                    Todos
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-preset="ninguno">
                                    Limpiar
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="row g-3">
                        @foreach ($catalogo as $clave => $info)
                            <div class="col-md-6">
                                <label class="border rounded-3 p-3 d-flex align-items-start gap-3 h-100 w-100" style="cursor: pointer; background: #faf9fe;">
                                    <input
                                        type="checkbox"
                                        name="permisos[]"
                                        value="{{ $clave }}"
                                        class="form-check-input mt-1 permiso-checkbox"
                                        @checked($esAdminRol || in_array($clave, $seleccionados, true))
                                        @disabled($esAdminRol)
                                    >
                                    @if ($esAdminRol)
                                        <input type="hidden" name="permisos[]" value="{{ $clave }}">
                                    @endif
                                    <span class="d-block flex-grow-1">
                                        <span class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-semibold text-dark">
                                                <i class="bi {{ $info['icono'] }} text-primary me-1"></i>{{ $info['titulo'] }}
                                            </span>
                                            <span class="badge text-bg-light border small">{{ $info['grupo'] }}</span>
                                        </span>
                                        <small class="text-body-secondary d-block">{{ $info['descripcion'] }}</small>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    @if (! $esAdminRol)
        <script>
            document.querySelectorAll('[data-preset]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const preset = this.getAttribute('data-preset');
                    const presets = {
                        recepcion: ['recepcion', 'socios', 'suscripciones', 'pagos'],
                        entrenador: ['clases', 'inscripciones'],
                    };

                    document.querySelectorAll('.permiso-checkbox').forEach(function (checkbox) {
                        if (preset === 'todos') {
                            checkbox.checked = true;
                        } else if (preset === 'ninguno') {
                            checkbox.checked = false;
                        } else if (presets[preset]) {
                            checkbox.checked = presets[preset].includes(checkbox.value);
                        }
                    });
                });
            });
        </script>
    @endif
@endsection
