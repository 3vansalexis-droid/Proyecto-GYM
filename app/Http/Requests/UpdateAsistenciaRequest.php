<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsistenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'socio_id' => ['required', 'integer', 'exists:socios,id'],
            'usuario_id' => ['required', 'integer', 'exists:usuarios,id'],
            'fecha_hora' => ['required', 'date'],
        ];
    }
}
