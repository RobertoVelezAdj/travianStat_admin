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
        Schema::create('aldea_producion', function (Blueprint $table) {
            $table->id();
            $table->integer('id_aldea');
            $table->integer('madera');
            $table->integer('barro');
            $table->integer('hierro');
            $table->integer('cereal');
            $table->integer('puntos_cultura');
            $table->date('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aldea_producion');
    }
};
