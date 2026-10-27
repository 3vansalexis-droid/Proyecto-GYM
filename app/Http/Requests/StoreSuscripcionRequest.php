<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSuscripcionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'socio_id' => ['required', 'integer', 'exists:socios,id'],
            'tipo_membresia_id' => [
                'required',
                'integer',
                Rule::exists('tipos_membresia', 'id')->where('activo', true),
            ],
            'fecha_inicio' => ['required', 'date'],
            'metodo_pago' => ['required', 'in:efectivo,tarjeta_debito,tarjeta_credito,transferencia'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'socio_id.required' => 'Debe seleccionar un socio.',
            'tipo_membresia_id.required' => 'Debe seleccionar una membresía.',
            'fecha_inicio.required' => 'La fecha de inicio es requerida.',
            'metodo_pago.required' => 'Debe seleccionar un método de pago.',
        ];
    }
}
