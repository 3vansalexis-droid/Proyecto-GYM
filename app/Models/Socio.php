<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Socio extends Model
{
    protected $table = 'socios';

    public $timestamps = false;

    protected $fillable = [
        'clave_socio',
        'nombre',
        'apellidos',
        'telefono',
        'email',
        'fecha_nacimiento',
        'fecha_registro',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'fecha_registro' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class, 'socio_id');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'socio_id');
    }

    public function inscripcionClases(): HasMany
    {
        return $this->hasMany(InscripcionClase::class, 'socio_id');
    }
}
