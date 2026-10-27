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

    
    Route::resource('roles', RolesController::class)->parameters(['roles' => 'rol']);
    Route::resource('usuarios', UsuariosController::class);
    Route::resource('socios', SociosController::class);
    Route::get('/asistencias/recepcion', [AsistenciasController::class, 'create'])->name('asistencias.create');
    Route::post('/asistencias/recepcion', [AsistenciasController::class, 'store'])->name('asistencias.store');
    Route::resource('asistencias', AsistenciasController::class);

   
    Route::resource('tipos-membresia', TipoMembresiaController::class);
    Route::resource('suscripciones', SuscripcionController::class)->parameters(['suscripciones' => 'suscripcion']);
    Route::resource('pagos', PagoController::class);
    Route::resource('entrenadores', EntrenadorController::class)->parameters(['entrenadores' => 'entrenador']);
    Route::resource('clases', ClaseController::class);
    Route::resource('inscripcion-clases', InscripcionClaseController::class);
});

