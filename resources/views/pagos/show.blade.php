@extends('layouts.app')

@section('title', 'Detalle del pago')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <h2 class="h4 mb-4">Pago #{{ $pago->id }}</h2>
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
            </dl>
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">Volver</a>
            </div>
        </div>
    </div>
@endsection
