<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('motocicletas', function (Blueprint $table) {
            $table->string('anio_modelo', 4)->nullable()->after('modelo_moto_otro');
        });
    }

    public function down(): void
    {
        Schema::table('motocicletas', function (Blueprint $table) {
            $table->dropColumn('anio_modelo');
        });
    }
};
