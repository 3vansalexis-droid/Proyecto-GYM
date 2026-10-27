@extends('layouts.app')

@section('title', 'Inscribir socio a clase')

@section('content')
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="card-header">Inscribir socio a clase</div>
        <div class="card-body p-4">
            @if (session('error'))
                <div class="alert alert-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('inscripciones.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Socio (con membresía vigente)</label>
                    <select name="socio_id" class="form-select" required>
                        <option value="">-- Seleccionar socio --</option>
                        @foreach ($socios as $socio)
                            @php($tieneVigente = $socio->suscripcionesVigentes->isNotEmpty())
                            <option
                                value="{{ $socio->id }}"
                                @selected(old('socio_id') == $socio->id)
                                @disabled(! $tieneVigente)
                            >
                                {{ $socio->nombre }} {{ $socio->apellidos ?? '' }} ({{ $socio->clave_socio }})
                                @if (! $tieneVigente)
                                    — [Sin membresía activa]
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Clase</label>
                    <select name="clase_id" class="form-select" required>
                        <option value="">-- Seleccionar clase --</option>
                        @foreach ($clases as $clase)
                            @php
                                $inscritos = $clase->inscripciones_count ?? 0;
                                $llena = $inscritos >= $clase->cupo_maximo;
                            @endphp
                            <option
                                value="{{ $clase->id }}"
                                @selected(old('clase_id') == $clase->id)
                                @disabled($llena)
                            >
                                {{ $clase->nombre }} ({{ $clase->dia_semana }} {{ optional($clase->hora_inicio)->format('H:i') }} - {{ optional($clase->hora_fin)->format('H:i') }} · Ocupación: {{ $inscritos }}/{{ $clase->cupo_maximo }})
                                @if ($llena)
                                    — [Cupo lleno]
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('inscripciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Completar inscripción</button>
                </div>
            </form>
        </div>
    </div>
@endsection
