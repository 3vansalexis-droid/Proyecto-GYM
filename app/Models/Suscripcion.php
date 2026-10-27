<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suscripcion extends Model
{
    protected $table = 'suscripciones';

    public $timestamps = false;

    protected $fillable = [
        'socio_id',
        'tipo_membresia_id',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function tipoMembresia(): BelongsTo
    {
        return $this->belongsTo(TipoMembresia::class, 'tipo_membresia_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'suscripcion_id');
    }

    public function costoTotal(): float
    {
        return round((float) ($this->tipoMembresia?->precio ?? 0), 2);
    }

    public function totalPagado(): float
    {
        if ($this->relationLoaded('pagos')) {
            $suma = $this->pagos
                ->whereNull('anulado_at')
                ->sum(fn (Pago $pago) => (float) $pago->monto);

            return round((float) $suma, 2);
        }

        return round((float) $this->pagos()->whereNull('anulado_at')->sum('monto'), 2);
    }

    public function saldoPendiente(): float
    {
        return max(0.0, round($this->costoTotal() - $this->totalPagado(), 2));
    }

    public function estaLiquidada(): bool
    {
        return $this->costoTotal() > 0 && $this->saldoPendiente() <= 0.0;
    }

    public function sincronizarEstadoPorPagos(): void
    {
        $this->loadMissing('tipoMembresia');
        $this->unsetRelation('pagos');

        if ($this->estaLiquidada()) {
            $nuevoEstado = ($this->fecha_fin && $this->fecha_fin->lt(today()))
                ? 'vencida'
                : 'activa';
        } else {
            $nuevoEstado = 'cancelada';
        }

        if ($this->estado !== $nuevoEstado) {
            $this->update(['estado' => $nuevoEstado]);
        }
    }
}
