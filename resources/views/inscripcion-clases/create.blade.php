@extends('layouts.app')

@section('title', 'Inscribir Socio a Clase')

@section('content')
<div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
    <div class="card-body p-4">
        <h4 class="mb-4 text-white">Inscribir Socio a Clase</h4>

        @if (session('error'))
            <div class="alert alert-danger mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('inscripciones.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Socio</label>
                <select name="socio_id" class="form-select" required>
                    <option value="">-- Seleccionar socio --</option>
                    @foreach ($socios as $socio)
                        <option value="{{ $socio->id }}" {{ old('socio_id') == $socio->id ? 'selected' : '' }}>
                            {{ $socio->nombre }} {{ $socio->apellidos ?? $socio->apellido ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Clase</label>
                <select name="clase_id" class="form-select" required>
                    <option value="">-- Seleccionar clase --</option>
                    @foreach ($clases as $clase)
                        <option value="{{ $clase->id }}" {{ old('clase_id') == $clase->id ? 'selected' : '' }}>
                            {{ $clase->nombre }} ({{ $clase->dia_semana }} {{ is_object($clase->hora_inicio) ? $clase->hora_inicio->format('H:i') : substr($clase->hora_inicio, 0, 5) }} - Cupo: {{ $clase->cupo_maximo }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('inscripciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Completar Inscripción</button>
            </div>
        </form>
    </div>
</div>
@endsection