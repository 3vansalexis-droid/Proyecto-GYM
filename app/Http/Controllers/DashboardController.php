<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Clase;
use App\Models\Entrenador;
use App\Models\Pago;
use App\Models\Socio;
use App\Models\Suscripcion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('dashboard', [
            'socios_count' => Socio::count(),
            'socios_activos' => Socio::where('activo', true)->count(),
            'suscripciones_activas' => Suscripcion::where('estado', 'activa')->count(),
            'pagos_hoy' => Pago::whereDate('fecha_pago', today())->count(),
            'clases_count' => Clase::count(),
            'entrenadores_count' => Entrenador::count(),
            'asistencias_hoy' => Asistencia::whereDate('fecha_hora', today())->count(),
        ]);
    }
}
