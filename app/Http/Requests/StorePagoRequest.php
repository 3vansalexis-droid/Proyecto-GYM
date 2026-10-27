<?php

namespace App\Http\Requests;

use App\Models\Suscripcion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'monto' => ['required', 'numeric', 'gt:0'],
            'metodo_pago' => ['required', 'in:efectivo,tarjeta_debito,tarjeta_credito,transferencia'],
            'fecha_pago' => ['required', 'date'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $suscripcion = Suscripcion::with(['tipoMembresia', 'pagos'])
                    ->find($this->input('suscripcion_id'));

                if (! $suscripcion) {
                    return;
                }

                if ($suscripcion->estaLiquidada()) {
                    $validator->errors()->add(
                        'suscripcion_id',
                        'Esta suscripción ya se encuentra 100% liquidada.'
                    );

                    return;
                }

                $otraVigente = Suscripcion::where('socio_id', $suscripcion->socio_id)
                    ->where('id', '!=', $suscripcion->id)
                    ->where('estado', 'activa')
                    ->whereDate('fecha_fin', '>=', today())
                    ->first();

                if ($otraVigente) {
                    $validator->errors()->add(
                        'suscripcion_id',
                        'No se puede activar esta suscripción porque el socio ya cuenta con otra suscripción vigente (#' . $otraVigente->id . ', vence el ' . $otraVigente->fecha_fin->format('d/m/Y') . ').'
                    );

                    return;
                }

                $saldo = $suscripcion->saldoPendiente();
                $monto = round((float) $this->input('monto'), 2);

                if ($monto > $saldo) {
                    $validator->errors()->add(
                        'monto',
                        'El monto excede el saldo pendiente de la suscripción ($' . number_format($saldo, 2) . ').'
                    );
                }
            },
        ];
    }
}
