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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string("cod")->unique();
            $table->unsignedBigInteger("cliente_id");
            $table->unsignedBigInteger("personal_id");
            $table->date("fecha");
            $table->time("hora_ini");
            $table->time("hora_fin");
            $table->double("total_horas");
            $table->string("ubicacion", 255);
            $table->string("tipo", 255)->nullable();
            $table->string("marca", 255)->nullable();
            $table->string("modelo", 255)->nullable();
            $table->string("nro_serie", 255)->nullable();
            $table->string("nro_parte", 255)->nullable();
            $table->string("nro_activo", 255)->nullable();
            $table->string("tipo_servicio", 255);
            $table->string("foto", 255)->nullable();
            $table->text("problema", 255);
            $table->text("trabajo_realizado", 255);
            $table->text("partes");
            $table->string("tipo_trabajo");
            $table->date("fecha_registro")->nullable();
            $table->timestamps();

            $table->foreign("cliente_id")->on("clientes")->references("id");
            $table->foreign("personal_id")->on("personals")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
