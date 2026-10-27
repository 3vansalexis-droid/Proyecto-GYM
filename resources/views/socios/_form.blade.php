<div class="row g-3">
    @isset($socio)
        <div class="col-md-6">
            <label for="clave_socio" class="form-label">Clave del socio</label>
            <input type="text" id="clave_socio" name="clave_socio" value="{{ old('clave_socio', $socio->clave_socio) }}" class="form-control @error('clave_socio') is-invalid @enderror" required>
            @error('clave_socio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endisset
    <div class="{{ isset($socio) ? 'col-md-6' : 'col-12' }}">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $socio->nombre ?? '') }}" class="form-control @error('nombre') is-invalid @enderror" required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="apellidos" class="form-label">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $socio->apellidos ?? '') }}" class="form-control @error('apellidos') is-invalid @enderror" required>
        @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $socio->telefono ?? '') }}" class="form-control @error('telefono') is-invalid @enderror">
        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" id="email" name="email" value="{{ old('email', $socio->email ?? '') }}" class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', isset($socio) && $socio->fecha_nacimiento ? $socio->fecha_nacimiento->format('Y-m-d') : '') }}" class="form-control @error('fecha_nacimiento') is-invalid @enderror">
        @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <div class="form-check">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" id="activo" name="activo" value="1" class="form-check-input" @checked(old('activo', $socio->activo ?? true))>
            <label for="activo" class="form-check-label">Socio activo</label>
        </div>
    </div>
</div>
