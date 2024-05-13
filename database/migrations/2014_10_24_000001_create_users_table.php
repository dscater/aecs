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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("personal_id")->nullable();
            $table->string('usuario', 255)->unique();
            $table->string('password');
            $table->string('tipo', 255);
            $table->integer('acceso')->default(1);
            $table->string('foto', 255)->nullable();
            $table->date("fecha_registro");
            $table->timestamps();

            $table->foreign("personal_id")->on("personals")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
