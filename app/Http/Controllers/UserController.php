<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    public static $permisos = [
        "GERENTE TÉCNICO" => [
            "usuarios.index",
            "usuarios.create",
            "usuarios.edit",
            "usuarios.destroy",

            "personals.index",
            "personals.create",
            "personals.edit",
            "personals.destroy",

            "clientes.index",
            "clientes.create",
            "clientes.edit",
            "clientes.destroy",

            "solicitud_atencions.index",
            "solicitud_atencions.create",
            "solicitud_atencions.edit",
            "solicitud_atencions.destroy",

            "servicios.index",
            "servicios.create",
            "servicios.edit",
            "servicios.destroy",

            "equipo_accesorios.index",
            "equipo_accesorios.create",
            "equipo_accesorios.edit",
            "equipo_accesorios.destroy",

            "configuracions.index",
            "configuracions.create",
            "configuracions.edit",
            "configuracions.destroy",

            "reportes.usuarios",
            "reportes.inventario_equipos",
            "reportes.servicios",
            "reportes.hora_servicios",
            "reportes.solicitud_atencion",
            "reportes.personal",
        ],
        "TÉCNICO SENIOR" => [],
        "TÉCNICO JUNIOR" => [],
        "TÉCNICO PASANTE" => [],
    ];

    public static function getPermisosUser()
    {
        $array_permisos = self::$permisos;
        if ($array_permisos[Auth::user()->tipo]) {
            return $array_permisos[Auth::user()->tipo];
        }
        return [];
    }


    public static function verificaPermiso($permiso)
    {
        if (in_array($permiso, self::$permisos[Auth::user()->tipo])) {
            return true;
        }
        return false;
    }

    public function permisos(Request $request)
    {
        return response()->JSON([
            "permisos" => $this->permisos[Auth::user()->tipo]
        ]);
    }

    public function getUser()
    {
        return response()->JSON([
            "user" => Auth::user()
        ]);
    }
}
