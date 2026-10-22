<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripcion_clases', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('clase_id')->constrained('clases');
            $table->foreignId('socio_id')->constrained('socios');
            $table->date('fecha_inscripcion')->useCurrent();
            $table->unique(['clase_id', 'socio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripcion_clases');
    }
};
