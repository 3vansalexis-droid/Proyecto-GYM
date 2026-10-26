<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AsistenciasController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SociosController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('asistencias.create');
    })->middleware('auth');

    Route::resource('roles', RolesController::class)->parameters(['roles' => 'rol']);
    Route::resource('socios', SociosController::class);
    Route::resource('usuarios', UsuariosController::class);

    Route::get('/asistencias/recepcion', [AsistenciasController::class, 'create'])->name('asistencias.create');
    Route::post('/asistencias/recepcion', [AsistenciasController::class, 'store'])->name('asistencias.store');
});
