<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\Servicio;
use App\Models\SolicitudAtencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class InicioController extends Controller
{
    public function inicio()
    {
        $array_infos = UserController::getInfoBoxUser();
        return Inertia::render('Home', compact('array_infos'));
    }

    public function getMaximoImagenes()
    {
        $maximo_archivos = (int)ini_get("max_file_uploads");
        return response()->JSON($maximo_archivos);
    }

    public function graf1(Request $request)
    {
        $filtro = $request->filtro;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $personals = Personal::where("status", 1)->get();
        if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
            $personals = Personal::where("status", 1)->where("id", Auth::user()->personal->id)->get();
        }

        $data = [];

        foreach ($personals as $item) {
            $solicitud_atencions = 0;
            if ($filtro == 'Rango de Fechas' && $fecha_ini && $fecha_fin) {
                $solicitud_atencions = SolicitudAtencion::where("personal_id", $item->id)
                    ->whereBetween("fecha", [$fecha_ini, $fecha_fin])
                    ->where("status", 1)
                    ->count();
            } else {
                $solicitud_atencions = SolicitudAtencion::where("personal_id", $item->id)
                    ->where("status", 1)
                    ->count();
            }
            $data[] = [$item->full_name, (int)$solicitud_atencions];
        }

        return response()->JSON([
            "data" => $data
        ]);
    }

    public function graf2(Request $request)
    {
        $filtro = $request->filtro;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $personals = Personal::where("status", 1)->get();
        if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
            $personals = Personal::where("status", 1)->where("id", Auth::user()->personal->id)->get();
        }
        $estados = ["PENDIENTE", "EN PROCESO", "ATENDIDO"];
        $data = [];

        foreach ($personals as $item) {
            $categories[] = $item->full_name;
        }

        foreach ($estados as $key => $value) {
            $data[] = [
                "name" => $value,
                "data" => []
            ];
            foreach ($personals as $item) {
                $solicitud_atencions = SolicitudAtencion::select("solicitud_atencions.*")->where("status", 1);
                $solicitud_atencions->where("personal_id", $item->id);
                $solicitud_atencions->where("estado", $value);
                if ($filtro == 'Rango de Fechass' && $fecha_fin && $fecha_fin) {
                    $solicitud_atencions->whereBetween("fecha", [$fecha_ini, $fecha_fin]);
                }
                $solicitud_atencions = $solicitud_atencions->count();
                $data[$key]["data"][] = (float)$solicitud_atencions;
            }
        }

        return response()->JSON([
            "categories" => $categories,
            "data" => $data,
        ]);
    }

    public function graf3(Request $request)
    {
        $filtro = $request->filtro;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $personals = Personal::where("status", 1)->get();
        if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
            $personals = Personal::where("status", 1)->where("id", Auth::user()->personal->id)->get();
        }
        $data = [];

        foreach ($personals as $item) {
            $solicitud_atencions = 0;
            if ($filtro == 'Rango de Fechas' && $fecha_ini && $fecha_fin) {
                $solicitud_atencions = Servicio::where("personal_id", $item->id)
                    ->whereBetween("fecha", [$fecha_ini, $fecha_fin])
                    ->where("status", 1)
                    ->count();
            } else {
                $solicitud_atencions = Servicio::where("personal_id", $item->id)
                    ->where("status", 1)
                    ->count();
            }
            $data[] = [$item->full_name, $solicitud_atencions];
        }

        return response()->JSON([
            "data" => $data
        ]);
    }

    public function graf4(Request $request)
    {
        $filtro = $request->filtro;
        $categories = [];
        $data1 = [];
        $data2 = [];
        $data3 = [];
        $servicio_inicial = Servicio::first();
        $servicio_final = Servicio::get()->last();
        $solicitud_inicial = SolicitudAtencion::first();
        $solicitud_final = SolicitudAtencion::get()->last();
        if ($filtro == 'anio') {
            $anios = [];
            if ($solicitud_inicial) {
                $anio_inicial = date("Y", strtotime($solicitud_inicial->fecha));
                $anio_final = date("Y", strtotime($solicitud_final->fecha));
                $anio_inicial = (int)$anio_inicial;
                $anio_final = (int)$anio_final;
                if ($anio_final < date("Y")) {
                    $anio_final  = date("Y");
                }
                $anio_inicial = (int)$anio_inicial;
                $anio_final = (int)$anio_final;
                for ($i = $anio_inicial; $i <= $anio_final; $i++) {
                    $anios[] = $i;
                }
            } else {
                $anios = [date("Y")];
            }

            // GRAFICOS ANUAL
            foreach ($anios as $key => $value) {
                // graf 4
                $solicitud_atencions = SolicitudAtencion::where("fecha", "LIKE", "$value%")->where("status", 1);
                if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                    $solicitud_atencions->where("personal_id", Auth::user()->personal->id);
                }
                $solicitud_atencions = $solicitud_atencions->count();
                $data1[] = [$value . "", (int)$solicitud_atencions];
                $categories[] = $value . "";
            }

            // graf 5
            $estados = ["PENDIENTE", "EN PROCESO", "ATENDIDO"];
            foreach ($estados as $key => $estado) {
                $data2[] = [
                    "name" => $estado,
                    "data" => [],
                ];
                foreach ($anios as $value) {
                    $solicitud_atencions = SolicitudAtencion::where("fecha", "LIKE", "$value%")->where("estado", $estado)->where("status", 1);
                    if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                        $solicitud_atencions->where("personal_id", Auth::user()->personal->id);
                    }
                    $solicitud_atencions = $solicitud_atencions->count();
                    $data2[$key]["data"][] = (int)$solicitud_atencions;
                }
            }

            // graf 6
            foreach ($anios as $value) {
                // graf 6
                $servicios = Servicio::where("fecha", "LIKE", "$value%")->where("status", 1);
                if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                    $servicios->where("personal_id", Auth::user()->personal->id);
                }
                $servicios = $servicios->count();
                $data3[] = [$value . "", (int)$servicios];
            }
        }
        if ($filtro == 'mes') {
            $anio = date("Y");
            $meses_num = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
            $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $mes_actual = (int)date("m");
            // GRAFICOS MES
            for ($i = 0; $i < $mes_actual; $i++) {
                $anio_mes = $anio . '-' . $meses_num[$i];
                // graf 4
                $solicitud_atencions = SolicitudAtencion::where("fecha", "LIKE", "$anio_mes%")->where("status", 1);
                if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                    $solicitud_atencions->where("personal_id", Auth::user()->personal->id);
                }
                $solicitud_atencions = $solicitud_atencions->count();
                $data1[] = [$meses[$i], (int)$solicitud_atencions];
                $categories[] = $meses[$i];
            }

            // graf 5
            $estados = ["PENDIENTE", "EN PROCESO", "ATENDIDO"];
            foreach ($estados as $key => $estado) {
                $data2[] = [
                    "name" => $estado,
                    "data" => [],
                ];
                for ($i = 0; $i < $mes_actual; $i++) {
                    $anio_mes = $anio . '-' . $meses_num[$i];
                    $solicitud_atencions = SolicitudAtencion::where("fecha", "LIKE", "$anio_mes%")->where("estado", $estado)->where("status", 1);
                    if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                        $solicitud_atencions->where("personal_id", Auth::user()->personal->id);
                    }
                    $solicitud_atencions = $solicitud_atencions->count();
                    $data2[$key]["data"][] = (int)$solicitud_atencions;
                }
            }

            // graf 6
            for ($i = 0; $i < $mes_actual; $i++) {
                $anio_mes = $anio . '-' . $meses_num[$i];
                // graf 6
                $servicios = Servicio::where("fecha", "LIKE", "$anio_mes%")->where("status", 1);
                if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                    $servicios->where("personal_id", Auth::user()->personal->id);
                }
                $servicios = $servicios->count();
                $data3[] = [$meses[$i], (int)$servicios];
            }
        }
        if ($filtro == 'dia') {
            $anio = date("Y");
            $meses_num = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
            $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $fecha_inicial = date("Y-m-d", strtotime($anio . "-01-01"));
            $fecha_inicial_aux = $fecha_inicial;
            $fecha_final = date("Y-m-d");

            $mes_actual = (int)date("m");
            // GRAFICOS MES
            while ($fecha_inicial_aux <= $fecha_final) {
                // graf 4
                $solicitud_atencions = SolicitudAtencion::where("fecha", $fecha_inicial_aux)->where("status", 1);
                if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                    $solicitud_atencions->where("personal_id", Auth::user()->personal->id);
                }
                $solicitud_atencions = $solicitud_atencions->count();
                $data1[] = [$fecha_inicial_aux, (int)$solicitud_atencions];
                $categories[] = $fecha_inicial_aux;
                $fecha_inicial_aux = date("Y-m-d", strtotime($fecha_inicial_aux . " +1 days"));
            }

            // graf 5
            $estados = ["PENDIENTE", "EN PROCESO", "ATENDIDO"];
            foreach ($estados as $key => $estado) {
                $data2[] = [
                    "name" => $estado,
                    "data" => [],
                ];
                $fecha_inicial_aux = $fecha_inicial;
                while ($fecha_inicial_aux <= $fecha_final) {
                    $solicitud_atencions = SolicitudAtencion::where("fecha", $fecha_inicial_aux)->where("estado", $estado)->where("status", 1);
                    if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                        $solicitud_atencions->where("personal_id", Auth::user()->personal->id);
                    }
                    $solicitud_atencions = $solicitud_atencions->count();
                    $data2[$key]["data"][] = (int)$solicitud_atencions;
                    $fecha_inicial_aux = date("Y-m-d", strtotime($fecha_inicial_aux . " +1 days"));
                }
            }

            // graf 6
            $fecha_inicial_aux = $fecha_inicial;
            while ($fecha_inicial_aux <= $fecha_final) {
                // graf 6
                $servicios = Servicio::where("fecha", $fecha_inicial_aux)->where("status", 1);
                if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
                    $servicios->where("personal_id", Auth::user()->personal->id);
                }
                $servicios = $servicios->count();
                $data3[] = [$fecha_inicial_aux, (int)$servicios];
                $fecha_inicial_aux = date("Y-m-d", strtotime($fecha_inicial_aux . " +1 days"));
            }
        }
        return response()->JSON([
            "categories" => $categories,
            "data1" => $data1,
            "data2" => $data2,
            "data3" => $data3,
        ]);
    }
}
