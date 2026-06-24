<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Evita clientes duplicados por documento. Los NULL (clientes sin
            // documento, p. ej. VIN2) se consideran distintos en MySQL/SQLite.
            $table->unique(['tipo_documento', 'numero_documento'], 'clientes_documento_unique');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropUnique('clientes_documento_unique');
        });
    }
};
