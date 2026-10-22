<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_membresia', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre')->unique();
            $table->unsignedInteger('duracion_dias');
            $table->decimal('precio', 10, 2);
            $table->boolean('activo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_membresia');
    }
};
