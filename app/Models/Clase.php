<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clase extends Model
{
    protected $table = 'clases';

    public $timestamps = false;

    protected $fillable = [
        'entrenador_id',
        'nombre',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'cupo_maximo',
    ];

    protected function casts(): array
    {
        return [
            'hora_inicio' => 'datetime:H:i',
            'hora_fin' => 'datetime:H:i',
            'cupo_maximo' => 'integer',
        ];
    }

    public function entrenador(): BelongsTo
    {
        return $this->belongsTo(Entrenador::class, 'entrenador_id');
    }

    public function inscripcionClases(): HasMany
    {
        return $this->hasMany(InscripcionClase::class, 'clase_id');
    }

    public function inscripciones(): HasMany
    {
        return $this->inscripcionClases();
    }
}
