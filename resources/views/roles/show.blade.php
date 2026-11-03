@extends('layouts.app')

@section('title', 'Detalle del rol')

@section('content')
    @php
        $catalogo = $catalogoPermisos ?? \App\Models\Rol::CATALOGO_PERMISOS;
        $activos = $permisosActivos ?? $rol->permisosEfectivos();
        $esAdmin = Auth::user()?->esAdministrador();
    @endphp

    <div class="card mx-auto" style="max-width: 820px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <span class="text-body-secondary small text-uppercase fw-semibold">Perfil de acceso</span>
                    <h2 class="h4 mb-0 fw-bold">{{ $rol->nombre }}</h2>
                </div>
                <span class="badge text-bg-primary px-3 py-2">
                    {{ $rol->usuarios->count() }} {{ $rol->usuarios->count() === 1 ? 'usuario' : 'usuarios' }}
                </span>
            </div>

            <dl class="row mb-4">
                <dt class="col-sm-3 text-body-secondary">Descripción</dt>
                <dd class="col-sm-9">{{ $rol->descripcion ?? 'Sin descripción' }}</dd>
                <dt class="col-sm-3 text-body-secondary">Módulos activos</dt>
                <dd class="col-sm-9">
                    <span class="badge text-bg-light border text-dark me-1">Dashboard general</span>
                    @foreach ($activos as $clave)
                        @if (isset($catalogo[$clave]))
                            <span class="badge text-bg-primary me-1 mb-1">
                                <i class="bi {{ $catalogo[$clave]['icono'] }} me-1"></i>{{ $catalogo[$clave]['titulo'] }}
                            </span>
                        @endif
                    @endforeach
                </dd>
            </dl>

            <h3 class="h6 fw-bold mb-3">Desglose de permisos</h3>
            <div class="row g-2 mb-4">
                @foreach ($catalogo as $clave => $info)
                    @php
                        $habilitado = in_array($clave, $activos, true);
                    @endphp
                    <div class="col-md-6">
                        <div class="border rounded-3 p-2 px-3 d-flex align-items-center justify-content-between {{ $habilitado ? 'bg-light' : 'opacity-50' }}">
                            <span>
                                <i class="bi {{ $info['icono'] }} {{ $habilitado ? 'text-primary' : 'text-secondary' }} me-2"></i>
                                <strong class="small">{{ $info['titulo'] }}</strong>
                            </span>
                            @if ($habilitado)
                                <span class="badge text-bg-success">Permitido</span>
                            @else
                                <span class="badge text-bg-secondary">Sin acceso</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Volver</a>
                @if ($esAdmin)
                    <a href="{{ route('roles.edit', $rol) }}" class="btn btn-primary">Editar permisos</a>
                    @if (mb_strtolower(trim((string) $rol->nombre)) !== 'administrador')
                        <form method="POST" action="{{ route('roles.destroy', $rol) }}" onsubmit="return confirm('¿Deseas eliminar este rol?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
