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
        Schema::create('apuestas', function (Blueprint $table) {
            $table->id();
            $table->string('id_usuario');
            $table->string('porcentaje');
            $table->string('dineroApostado');
            $table->string('descripcion');
            $table->string('resultado');
            $table->string('resultadoDinero');
            $table->string('deporte');
            $table->string('stack');
            $table->string('probabilidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apuestas');
    }
};
