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
        Schema::create('velocidad_servidores', function (Blueprint $table) {
            $table->id();
            $table->integer('velocidad');
            $table->float('tiempo_entreno', 8, 2);
            $table->float('tiempo_construccion', 8, 2);
            $table->float('velocidad_tropas', 8, 2);
            $table->float('produccion_recursos', 8, 2);
            $table->float('pc_max_fiesta_pequeña', 8, 2);
            $table->float('pc_max_fiesta_grande', 8, 2);
            $table->float('velocidad_fiesta', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('velocidad_servidores');
    }
};
