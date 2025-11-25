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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->date('fecha_ingreso');
            $table->unsignedBigInteger('id_promocion')->nullable();
            $table->double('precio');
            $table->unsignedBigInteger('id_categoria')->nullable();
            $table->string('state')->default('a');
            $table->integer('stock');
            $table->timestamps();

            $table->foreign('id_categoria')->references('id')->on('categorias');
            $table->foreign('id_promocion')->references('id')->on('promociones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
