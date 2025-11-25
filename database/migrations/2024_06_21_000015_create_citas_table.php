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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->string('fecha_hora');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_medico');
            $table->unsignedBigInteger('id_secretaria');
            $table->unsignedBigInteger('id_servicio');
            $table->string('state')->default('a');
            $table->timestamps();

            $table->foreign('id_cliente')->references('id')->on('clientes');
            $table->foreign('id_medico')->references('id')->on('users');
            $table->foreign('id_secretaria')->references('id')->on('users');
            $table->foreign('id_servicio')->references('id')->on('servicios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
