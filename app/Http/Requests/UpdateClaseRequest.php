<?php

namespace App\Http\Requests;

use App\Models\Clase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateClaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entrenador_id' => ['required', 'integer', 'exists:entrenadores,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'dia_semana' => ['required', 'string', 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'cupo_maximo' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $claseParam = $this->route('clase');
                $claseId = $claseParam instanceof Clase ? $claseParam->id : $claseParam;

                $traslape = Clase::where('entrenador_id', $this->input('entrenador_id'))
                    ->where('id', '!=', $claseId)
                    ->where('dia_semana', $this->input('dia_semana'))
                    ->where('hora_inicio', '<', $this->input('hora_fin'))
                    ->where('hora_fin', '>', $this->input('hora_inicio'))
                    ->exists();

                if ($traslape) {
                    $validator->errors()->add(
                        'hora_inicio',
                        'El entrenador seleccionado ya tiene otra clase asignada en ese día y horario.'
                    );
                }
            },
        ];
    }
}
