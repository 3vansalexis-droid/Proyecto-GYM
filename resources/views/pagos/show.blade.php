@extends('layouts.app')

@section('title', 'Detalle del pago')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="h4 mb-0">Pago #{{ $pago->id }}</h2>
                @if ($pago->estaAnulado())
                    <span class="badge text-bg-danger">Anulado</span>
                @else
                    <span class="badge text-bg-success">Válido</span>
                @endif
            </div>
            <dl class="row mb-0">
                <dt class="col-sm-4">Suscripción</dt>
                <dd class="col-sm-8">
                    #{{ $pago->suscripcion?->id ?? '—' }}
                    @if ($pago->suscripcion?->socio)
                        · {{ $pago->suscripcion->socio->nombre }} {{ $pago->suscripcion->socio->apellidos }}
                    @endif
                </dd>
                <dt class="col-sm-4">Usuario</dt>
                <dd class="col-sm-8">{{ $pago->usuario?->nombre ?? '—' }}</dd>
                <dt class="col-sm-4">Monto</dt>
                <dd class="col-sm-8">${{ number_format((float) $pago->monto, 2) }}</dd>
                <dt class="col-sm-4">Método</dt>
                <dd class="col-sm-8">{{ ucfirst(str_replace('_', ' ', $pago->metodo_pago)) }}</dd>
                <dt class="col-sm-4">Fecha</dt>
                <dd class="col-sm-8">{{ optional($pago->fecha_pago)->format('d/m/Y H:i') }}</dd>
                @if ($pago->estaAnulado())
                    <dt class="col-sm-4">Anulado el</dt>
                    <dd class="col-sm-8">{{ optional($pago->anulado_at)->format('d/m/Y H:i') }}</dd>
                    <dt class="col-sm-4">Anulado por</dt>
                    <dd class="col-sm-8">{{ $pago->anulador?->nombre ?? '—' }}</dd>
                    <dt class="col-sm-4">Motivo</dt>
                    <dd class="col-sm-8">{{ $pago->motivo_anulacion }}</dd>
                @endif
            </dl>
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">Volver</a>
            </div>
        </div>
    </div>
@endsection
