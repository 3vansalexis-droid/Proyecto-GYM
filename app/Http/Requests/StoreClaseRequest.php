<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaseRequest extends FormRequest
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
            'dia_semana' => ['required', 'string', 'max:20'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'cupo_maximo' => ['required', 'integer', 'min:1'],
        ];
    }
}
