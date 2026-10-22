<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMembresia extends Model
{
    protected $table = 'tipos_membresia';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'duracion_dias',
        'precio',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'duracion_dias' => 'integer',
            'precio' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class, 'tipo_membresia_id');
    }
}
