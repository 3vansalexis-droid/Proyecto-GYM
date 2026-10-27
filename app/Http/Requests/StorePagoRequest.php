<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'suscripcion_id' => ['required', 'integer', 'exists:suscripciones,id'],
            'monto' => ['required', 'numeric', 'min:0'],
            'metodo_pago' => ['required', 'in:efectivo,tarjeta_debito,tarjeta_credito,transferencia'],
            'fecha_pago' => ['required', 'date'],
        ];
    }
}
