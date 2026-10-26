@extends('layouts.app')

@section('title', 'Editar socio')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Editar socio</h2>
                <a href="{{ route('socios.index') }}" class="btn btn-outline-light">Volver</a>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('socios.update', $socio) }}">
                        @csrf
                        @method('PUT')
                        @include('socios._form')
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
