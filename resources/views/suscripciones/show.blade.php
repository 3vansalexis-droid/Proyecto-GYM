@extends('layouts.app')

@section('title', 'Detalle de la suscripción')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <h2 class="h4 mb-4">Suscripción #{{ $suscripcion->id }}</h2>
            <dl class="row mb-0">
                <dt class="col-sm-4">Socio</dt>
                <dd class="col-sm-8">{{ $suscripcion->socio?->nombre }} {{ $suscripcion->socio?->apellidos }}</dd>
                <dt class="col-sm-4">Tipo</dt>
                <dd class="col-sm-8">{{ $suscripcion->tipoMembresia?->nombre ?? '—' }}</dd>
                <dt class="col-sm-4">Fecha inicio</dt>
                <dd class="col-sm-8">{{ optional($suscripcion->fecha_inicio)->format('d/m/Y') }}</dd>
                <dt class="col-sm-4">Fecha fin</dt>
                <dd class="col-sm-8">{{ optional($suscripcion->fecha_fin)->format('d/m/Y') }}</dd>
                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8">{{ ucfirst($suscripcion->estado) }}</dd>
            </dl>
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('suscripciones.index') }}" class="btn btn-outline-secondary">Volver</a>
            </div>
        </div>
    </div>
@endsection
