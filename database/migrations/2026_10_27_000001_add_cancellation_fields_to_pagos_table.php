<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table): void {
            $table->timestamp('anulado_at')->nullable()->after('fecha_pago');
            $table->foreignId('anulado_por')->nullable()->after('anulado_at')->constrained('usuarios');
            $table->string('motivo_anulacion')->nullable()->after('anulado_por');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table): void {
            $table->dropForeign(['anulado_por']);
            $table->dropColumn(['anulado_at', 'anulado_por', 'motivo_anulacion']);
        });
    }
};
