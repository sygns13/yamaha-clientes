<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motocicletas', function (Blueprint $table) {
            // Necesitamos que modelo_moto_id sea nullable para cuando el usuario elige "OTROS"
            $table->dropForeign(['modelo_moto_id']);
            $table->unsignedBigInteger('modelo_moto_id')->nullable()->change();
            $table->foreign('modelo_moto_id')->references('id')->on('modelos_moto')->restrictOnDelete();

            // Campo libre para cuando el usuario escribe el modelo manualmente
            $table->string('modelo_moto_otro')->nullable()->after('modelo_moto_id');
        });
    }

    public function down(): void
    {
        Schema::table('motocicletas', function (Blueprint $table) {
            $table->dropColumn('modelo_moto_otro');

            $table->dropForeign(['modelo_moto_id']);
            $table->unsignedBigInteger('modelo_moto_id')->nullable(false)->change();
            $table->foreign('modelo_moto_id')->references('id')->on('modelos_moto')->restrictOnDelete();
        });
    }
};
