<?php

namespace App\Http\Requests;

use App\Models\Suscripcion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateSuscripcionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'socio_id' => ['required', 'integer', 'exists:socios,id'],
            'tipo_membresia_id' => ['required', 'integer', 'exists:tipos_membresia,id'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:activa,vencida,cancelada'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                if ($this->input('estado') !== 'activa') {
                    return;
                }

                /** @var Suscripcion|null $suscripcionActual */
                $suscripcionActual = $this->route('suscripcion');

                if ($suscripcionActual && ! $suscripcionActual->estaLiquidada()) {
                    $validator->errors()->add(
                        'estado',
                        'No se puede activar manualmente una suscripción con saldo pendiente. Liquídala primero en el módulo de Pagos.'
                    );

                    return;
                }

                $otraVigente = Suscripcion::where('socio_id', $this->input('socio_id'))
                    ->where('id', '!=', $suscripcionActual?->id)
                    ->where('estado', 'activa')
                    ->whereDate('fecha_fin', '>=', today())
                    ->first();

                if ($otraVigente) {
                    $validator->errors()->add(
                        'socio_id',
                        'El socio ya tiene otra suscripción activa y vigente (#' . $otraVigente->id . ', vence el ' . $otraVigente->fecha_fin->format('d/m/Y') . ').'
                    );
                }
            },
        ];
    }
}
