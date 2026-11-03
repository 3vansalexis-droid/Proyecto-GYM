<?php

use App\Http\Controllers\AsistenciasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntrenadorController;
use App\Http\Controllers\InscripcionClaseController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SociosController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\TipoMembresiaController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::middleware('role:permiso:administracion')->group(function () {
        Route::resource('roles', RolesController::class)->parameters(['roles' => 'rol']);
        Route::resource('usuarios', UsuariosController::class);
        Route::resource('asistencias', AsistenciasController::class)
            ->only(['edit', 'update', 'destroy']);
        Route::patch('/pagos/{pago}/anular', [PagoController::class, 'anular'])
            ->name('pagos.anular');
    });

    Route::middleware('role:permiso:tipos_membresia')->group(function () {
        Route::resource('tipos-membresia', TipoMembresiaController::class);
    });

    Route::middleware('role:permiso:entrenadores')->group(function () {
        Route::resource('entrenadores', EntrenadorController::class)->parameters(['entrenadores' => 'entrenador']);
    });

    Route::middleware('role:permiso:inscripciones')->group(function () {
        Route::resource('inscripcion-clases', InscripcionClaseController::class)
            ->only(['index', 'create', 'store', 'destroy'])
            ->names('inscripciones');

        Route::redirect('/inscripciones', '/inscripcion-clases');
        Route::redirect('/inscripciones/create', '/inscripcion-clases/create');
    });

    Route::middleware('role:permiso:socios')->group(function () {
        Route::resource('socios', SociosController::class);
    });

    Route::middleware('role:permiso:recepcion')->group(function () {
        Route::get('/asistencias/recepcion', [AsistenciasController::class, 'create'])->name('asistencias.create');
        Route::post('/asistencias/recepcion', [AsistenciasController::class, 'store'])->name('asistencias.store');
        Route::resource('asistencias', AsistenciasController::class)
            ->only(['index', 'show']);
    });

    Route::middleware('role:permiso:suscripciones')->group(function () {
        Route::resource('suscripciones', SuscripcionController::class)->parameters(['suscripciones' => 'suscripcion']);
    });

    Route::middleware('role:permiso:pagos')->group(function () {
        Route::resource('pagos', PagoController::class)->only(['index', 'create', 'store', 'show']);
    });

    Route::middleware('role:permiso:clases')->group(function () {
        Route::resource('clases', ClaseController::class);
    });
});
