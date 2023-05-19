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
        Schema::create('historico_apuestas_diario', function (Blueprint $table) {
            $table->id();
            $table->string('usuario');
            $table->string('dineroEnApuestas');
            $table->string('dineroStack');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_apuestas_diario');
    }
};
