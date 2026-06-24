<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Autorización (opcional) para fines promocionales. La aceptación de la
            // Política de Privacidad es obligatoria pero solo es un gate, no se persiste.
            $table->boolean('acepta_promociones')->default(false)->after('correo');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('acepta_promociones');
        });
    }
};
