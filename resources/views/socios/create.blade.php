@extends('layouts.app')

@section('title', 'Nuevo socio')

@section('content')
    <div class="card mx-auto" style="max-width: 800px;">
        <div class="card-header">Registrar nuevo socio</div>
        <div class="card-body p-4">
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('socios.store') }}">
                @csrf
                @include('socios._form')
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('socios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar socio</button>
                </div>
            </form>
        </div>
    </div>
@endsection
