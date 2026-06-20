<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motocicletas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('modelo_moto_id')->constrained('modelos_moto')->restrictOnDelete();
            $table->enum('tipo_formulario', ['VIN1', 'VIN2']);
            $table->string('vin_descripcion')->nullable();
            $table->string('vin_imagen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motocicletas');
    }
};
