<?php

namespace App\Http\Requests;

use App\Models\Suscripcion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreSuscripcionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'socio_id' => [
                'required',
                'integer',
                Rule::exists('socios', 'id')->where('activo', true),
            ],
            'tipo_membresia_id' => [
                'required',
                'integer',
                Rule::exists('tipos_membresia', 'id')->where('activo', true),
            ],
            'fecha_inicio' => ['required', 'date'],
            'metodo_pago' => ['nullable', 'in:efectivo,tarjeta_debito,tarjeta_credito,transferencia'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->has('socio_id')) {
                    return;
                }

                $vigente = Suscripcion::where('socio_id', $this->input('socio_id'))
                    ->where('estado', 'activa')
                    ->whereDate('fecha_fin', '>=', today())
                    ->orderByDesc('fecha_fin')
                    ->first();

                if ($vigente) {
                    $validator->errors()->add(
                        'socio_id',
                        'El socio ya cuenta con una suscripción activa y vigente (vence el ' . $vigente->fecha_fin->format('d/m/Y') . ').'
                    );

                    return;
                }

                $pendienteDePago = Suscripcion::where('socio_id', $this->input('socio_id'))
                    ->whereDate('fecha_fin', '>=', today())
                    ->whereDoesntHave('pagos')
                    ->first();

                if ($pendienteDePago) {
                    $validator->errors()->add(
                        'socio_id',
                        'El socio ya tiene una suscripción solicitada pendiente de liquidar (#' . $pendienteDePago->id . '). Liquídala en Pagos o elimínala antes de crear otra.'
                    );
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'socio_id.required' => 'Debe seleccionar un socio.',
            'socio_id.exists' => 'El socio seleccionado no existe o se encuentra inactivo.',
            'tipo_membresia_id.required' => 'Debe seleccionar una membresía.',
            'fecha_inicio.required' => 'La fecha de inicio es requerida.',
            'metodo_pago.in' => 'Seleccione un método de pago válido.',
        ];
    }
}
