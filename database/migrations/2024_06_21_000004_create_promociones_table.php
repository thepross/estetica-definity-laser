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
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->double('descuento');
            $table->string('fecha_inicio');
            $table->string('fecha_fin');
            $table->string('nombre');
            $table->string('state')->default('a');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promociones', function (Blueprint $table) {
            //
        });
    }
};
