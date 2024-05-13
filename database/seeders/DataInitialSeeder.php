<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DataInitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "usuario" => "admin",
            "password" => Hash::make("admin"),
            "tipo" => "GERENTE TÉCNICO",
            "fecha_registro" => date("Y-m-d")
        ]);

        Configuracion::create([
            "nombre_sistema" => "AECS",
            "alias" => "AECS",
            "razon_social" => "EMPRESA AECS S.A.",
            "ciudad" => "LA PAZ",
            "dir" => "LOS OLIVOS",
            "fono" => "2222222",
            "correo" => "ACES@GMAIL.COM",
            "web" => "AECS.COM",
            "actividad" => "ACTIVIDAD",
            "logo" => "logo.jpg"
        ]);
    }
}
