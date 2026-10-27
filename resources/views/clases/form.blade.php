@extends('layouts.app')

@section('title', isset($clase->id) ? 'Editar clase' : 'Nueva clase')

@section('content')
    <div class="card mx-auto" style="max-width: 800px;">
        <div class="card-header">{{ isset($clase->id) ? 'Editar clase' : 'Registrar nueva clase' }}</div>
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ isset($clase->id) ? route('clases.update', $clase->id) : route('clases.store') }}">
                @csrf
                @if (isset($clase->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la clase</label>
                        <input type="text" name="nombre" class="form-control"
                            value="{{ old('nombre', $clase->nombre ?? '') }}" placeholder="Ej. Spinning, Crossfit" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Entrenador asignado</label>
                        <select name="entrenador_id" class="form-select" required>
                            <option value="">-- Seleccionar entrenador --</option>
                            @foreach ($entrenadores as $entrenador)
                                <option value="{{ $entrenador->id }}" {{ old('entrenador_id', $clase->entrenador_id ?? '') == $entrenador->id ? 'selected' : '' }}>
                                    {{ $entrenador->nombre }} {{ $entrenador->apellidos ?? '' }} ({{ $entrenador->especialidad ?? 'General' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Día de la semana</label>
                        <select name="dia_semana" class="form-select" required>
                            <option value="">-- Seleccionar día --</option>
                            @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                <option value="{{ $dia }}" {{ old('dia_semana', $clase->dia_semana ?? '') === $dia ? 'selected' : '' }}>
                                    {{ $dia }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hora de inicio</label>
                        <input type="time" name="hora_inicio" class="form-control"
                            value="{{ old('hora_inicio', $clase->hora_inicio?->format('H:i') ?? '') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hora de fin</label>
                        <input type="time" name="hora_fin" class="form-control"
                            value="{{ old('hora_fin', $clase->hora_fin?->format('H:i') ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Cupo máximo de alumnos</label>
                        <input type="number" name="cupo_maximo" min="1" class="form-control"
                            value="{{ old('cupo_maximo', $clase->cupo_maximo ?? 15) }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('clases.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($clase->id) ? 'Guardar cambios' : 'Guardar clase' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
