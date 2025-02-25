<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Personal;
use App\Models\Servicio;
use App\Models\SolicitudAtencion;
use App\Models\User;
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

            "backup.index",

            "reportes.usuarios",
            "reportes.inventario_equipos",
            "reportes.servicios",
            "reportes.hora_servicios",
            "reportes.solicitud_atencion",
            "reportes.personal",
        ],
        "TÉCNICO SENIOR" => [
            "clientes.index",

            "solicitud_atencions.index",
            "solicitud_atencions.edit",

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

            "reportes.servicios",
            "reportes.solicitud_atencion",
        ],
        "TÉCNICO JUNIOR" => [
            "clientes.index",

            "solicitud_atencions.index",
            "solicitud_atencions.edit",

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

            "reportes.servicios",
            "reportes.solicitud_atencion",
        ],
        "TÉCNICO PASANTE" => [
            "clientes.index",

            "solicitud_atencions.index",
            "solicitud_atencions.edit",

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

            "reportes.servicios",
            "reportes.solicitud_atencion",
        ],
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

    public static function getInfoBoxUser()
    {
        $tipo = Auth::user()->tipo;
        $array_infos = [];

        if (in_array('personals.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'Personal',
                'cantidad' => count(Personal::where("status", 1)->get()),
                'color' => 'bg-blue-darken-2',
                'icon' => "mdi-account-badge",
                "url" => "personals.index"
            ];
        }

        if (in_array('clientes.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'Clientes',
                'cantidad' => count(Cliente::where("status", 1)->get()),
                'color' => 'bg-blue-darken-2',
                'icon' => "mdi-playlist-edit",
                "url" => "clientes.index"
            ];
        }

        if (in_array('solicitud_atencions.index', self::$permisos[$tipo])) {
            $registros = SolicitudAtencion::where("status", 1);
            if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                $registros->where("personal_id", Auth::user()->personal->id);
            }
            $registros = $registros->get();

            $array_infos[] = [
                'label' => 'Solicitud de Atención',
                'cantidad' => count($registros),
                'color' => 'bg-blue-darken-2',
                'icon' => "mdi-tag-multiple",
                "url" => "solicitud_atencions.index"
            ];
        }

        if (in_array('servicios.index', self::$permisos[$tipo])) {
            $registros = Servicio::where("status", 1);
            if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                $registros->where("personal_id", Auth::user()->personal->id);
            }
            $registros = $registros->get();

            $array_infos[] = [
                'label' => 'Servicios',
                'cantidad' => count($registros),
                'color' => 'bg-blue-darken-2',
                'icon' => "mdi-clipboard-list",
                "url" => "servicios.index"
            ];
        }

        if (in_array('equipo_accesorios.index', self::$permisos[$tipo])) {

            $registros = Servicio::where("status", 1);
            if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                $registros->where("personal_id", Auth::user()->personal->id);
            }
            $registros = $registros->get();

            $array_infos[] = [
                'label' => 'Inventario de Equipos',
                'cantidad' => count($registros),
                'color' => 'bg-blue-darken-2',
                'icon' => "mdi-view-list",
                "url" => "equipo_accesorios.index"
            ];
        }


        if (in_array('usuarios.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'Usuarios',
                'cantidad' => count(User::where('id', '!=', 1)->get()),
                'color' => 'bg-blue-darken-2',
                'icon' => "mdi-account-group",
                "url" => "usuarios.index"
            ];
        }

        return $array_infos;
    }
}
