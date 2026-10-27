<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntrenadorController;
use App\Http\Controllers\InscripcionClaseController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\SuscripcionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoMembresiaController;

Route::get('/', DashboardController::class)->name('dashboard');

Route::resource('socios', SocioController::class);
Route::resource('tipos-membresia', TipoMembresiaController::class);
Route::resource('suscripciones', SuscripcionController::class);
Route::resource('pagos', PagoController::class);
Route::resource('asistencias', AsistenciaController::class);
Route::resource('entrenadores', EntrenadorController::class);
Route::resource('clases', ClaseController::class);
Route::resource('inscripcion-clases', InscripcionClaseController::class);
