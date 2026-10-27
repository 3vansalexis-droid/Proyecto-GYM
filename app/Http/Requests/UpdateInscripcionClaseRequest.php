<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInscripcionClaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clase_id' => ['required', 'integer', 'exists:clases,id'],
            'socio_id' => ['required', 'integer', 'exists:socios,id'],
            'fecha_inscripcion' => ['required', 'date'],
        ];
    }
}
