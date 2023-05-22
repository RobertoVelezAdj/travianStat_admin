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
        Schema::create('aldea_tropas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_aldea');
            $table->integer('tropa_1');
            $table->integer('tropa_2');
            $table->integer('tropa_3');
            $table->integer('tropa_4');
            $table->integer('tropa_5');
            $table->integer('tropa_6');
            $table->integer('tropa_7');
            $table->integer('tropa_8');
            $table->integer('tropa_9');
            $table->integer('tropa_10');
            $table->integer('tropa_11');
            $table->date('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aldea_tropas');
    }
};
