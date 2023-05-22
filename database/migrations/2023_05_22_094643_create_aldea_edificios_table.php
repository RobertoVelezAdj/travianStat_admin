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
        Schema::create('aldea_edificios', function (Blueprint $table) {
            $table->id();
            $table->integer('id_aldea');
            $table->integer('cuartel');
            $table->integer('cuartel_g');
            $table->integer('establo');
            $table->integer('establo_g');
            $table->integer('taller');
            $table->integer('ayuntamiento');
            $table->integer('p_torneos');
            $table->integer('o_comercio');
            $table->date('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aldea_edificios');
    }
};
