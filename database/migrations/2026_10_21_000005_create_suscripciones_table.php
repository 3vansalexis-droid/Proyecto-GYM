<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suscripciones', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('socio_id')->constrained('socios');
            $table->foreignId('tipo_membresia_id')->constrained('tipos_membresia');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['activa', 'vencida', 'cancelada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};
