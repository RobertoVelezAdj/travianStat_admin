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
        Schema::create('aldea', function (Blueprint $table) {
            $table->id();
            $table->integer('id_usuario');
            $table->integer('coord_x');
            $table->integer('coord_y');
            $table->string('nombre');
            $table->integer('tipo');
            $table->integer('fiesta_pequena');
            $table->integer('fiesta_grande');
            $table->date('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aldea');
    }
};
