@extends('layouts.app')

@section('title', 'Editar socio')

@section('content')
    <div class="card mx-auto" style="max-width: 800px;">
        <div class="card-header">Editar socio</div>
        <div class="card-body p-4">
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('socios.update', $socio) }}">
                @csrf
                @method('PUT')
                @include('socios._form')
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('socios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
@endsection
