<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\InscripcionClase;
use App\Models\Socio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InscripcionClaseController extends Controller
{
    public function index(): View
    {
        $inscripciones = InscripcionClase::with(['clase', 'socio'])
            ->orderBy('id', 'desc')
            ->get();

        return view('inscripcion-clases.index', compact('inscripciones'));
    }

    public function create(): View
    {
        $clases = Clase::with('entrenador')->orderBy('nombre')->get();
        $socios = Socio::orderBy('nombre')->get();

        return view('inscripcion-clases.create', compact('clases', 'socios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'clase_id' => 'required|exists:clases,id',
            'socio_id' => 'required|exists:socios,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $clase = Clase::where('id', $request->clase_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $totalInscritos = InscripcionClase::where('clase_id', $clase->id)->count();

                if ($totalInscritos >= $clase->cupo_maximo) {
                    throw new \RuntimeException('No hay cupo disponible en esta clase.');
                }

                $socio = Socio::findOrFail($request->socio_id);

                if (! $socio->activo || ! $socio->suscripciones()
                    ->where('estado', 'activa')
                    ->whereDate('fecha_fin', '>=', today())
                    ->exists()) {
                    throw new \RuntimeException('El socio debe tener una membresía activa y vigente.');
                }

                $yaInscrito = InscripcionClase::where('clase_id', $clase->id)
                    ->where('socio_id', $request->socio_id)
                    ->exists();

                if ($yaInscrito) {
                    throw new \RuntimeException('El socio ya está inscrito en esta clase.');
                }

                InscripcionClase::create([
                    'clase_id' => $clase->id,
                    'socio_id' => $request->socio_id,
                    'fecha_inscripcion' => now()->toDateString(),
                ]);
            });

            return redirect()->route('inscripciones.index')
                ->with('success', 'Socio inscrito correctamente en la clase.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id): RedirectResponse
    {
        $inscripcion = InscripcionClase::findOrFail($id);
        $inscripcion->delete();

        return redirect()->route('inscripciones.index')
            ->with('success', 'Inscripción cancelada correctamente.');
    }
}
