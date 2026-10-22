<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clases', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('entrenador_id')->constrained('entrenadores');
            $table->string('nombre');
            $table->string('dia_semana');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->unsignedInteger('cupo_maximo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clases');
    }
};
