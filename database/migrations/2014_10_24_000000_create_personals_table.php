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
        Schema::create('personals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('ci');
            $table->string('ci_exp');
            $table->string('estado_civil');
            $table->date('fecha_nac');
            $table->string('cel');
            $table->string('domicilio', 255)->nullable();
            $table->string('especialidad', 255);
            $table->string('record', 255)->nullable();
            $table->string('hoja_vida', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->date("fecha_registro");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personals');
    }
};
