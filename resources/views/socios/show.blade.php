@extends('layouts.app')

@section('title', 'Detalle del socio')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <h2 class="h4 mb-4">{{ $socio->nombre }} {{ $socio->apellidos }}</h2>
            <dl class="row mb-0">
                <dt class="col-sm-4">Clave</dt>
                <dd class="col-sm-8">{{ $socio->clave_socio }}</dd>
                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8">{{ $socio->email ?? '—' }}</dd>
                <dt class="col-sm-4">Teléfono</dt>
                <dd class="col-sm-8">{{ $socio->telefono ?? '—' }}</dd>
                <dt class="col-sm-4">Fecha de nacimiento</dt>
                <dd class="col-sm-8">{{ optional($socio->fecha_nacimiento)->format('d/m/Y') ?? '—' }}</dd>
                <dt class="col-sm-4">Fecha de registro</dt>
                <dd class="col-sm-8">{{ optional($socio->fecha_registro)->format('d/m/Y') ?? '—' }}</dd>
                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8">{{ $socio->activo ? 'Activo' : 'Inactivo' }}</dd>
            </dl>
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('socios.index') }}" class="btn btn-outline-secondary">Volver</a>
            </div>
        </div>
    </div>
@endsection
