<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('suscripcion_id')->constrained('suscripciones');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', [
                'efectivo',
                'tarjeta_debito',
                'tarjeta_credito',
                'transferencia',
            ]);
            $table->timestamp('fecha_pago')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
