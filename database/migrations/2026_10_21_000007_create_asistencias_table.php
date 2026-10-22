<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('socio_id')->constrained('socios');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->timestamp('fecha_hora')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
