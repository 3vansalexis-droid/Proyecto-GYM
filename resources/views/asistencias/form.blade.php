@extends('layouts.app')

@section('title', isset($asistencia->id) ? 'Editar asistencia' : 'Registrar asistencia')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 900px;">
        <div class="card-body p-4">
            <form method="POST" action="{{ isset($asistencia->id) ? route('asistencias.update', $asistencia) : route('asistencias.store') }}">
                @csrf
                @if (isset($asistencia->id))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Socio</label>
                        <input type="number" name="socio_id" class="form-control" value="{{ old('socio_id', $asistencia->socio_id ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usuario</label>
                        <input type="number" name="usuario_id" class="form-control" value="{{ old('usuario_id', $asistencia->usuario_id ?? '') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Fecha y hora</label>
                        <input type="datetime-local" name="fecha_hora" class="form-control" value="{{ old('fecha_hora', optional($asistencia->fecha_hora ?? null)->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('asistencias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
