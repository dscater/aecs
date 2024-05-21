<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Obra;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use PDF;

class ReporteController extends Controller
{
    public function usuarios()
    {
        return Inertia::render("Reportes/Usuarios");
    }

    public function r_usuarios(Request $request)
    {
        $tipo =  $request->tipo;
        $usuarios = User::join("personals", "personals.id", "=", "users.personal_id")
            ->where("personals.status", 1)
            ->orderBy("paterno", "ASC")->get();

        if ($tipo != 'TODOS') {
            $request->validate([
                'tipo' => 'required',
            ]);
            $usuarios = User::join("personals", "personals.id", "=", "users.personal_id")
                ->where("personals.status", 1)
                ->where('tipo', $request->tipo)->orderBy("paterno", "ASC")->get();
        }

        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('usuarios.pdf');
    }

    public function inventario_equipos()
    {
        return Inertia::render("Reportes/InventarioEquipos");
    }

    public function r_inventario_equipos(Request $request)
    {
        $servicio_id =  $request->servicio_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $servicios = Servicio::whereBetween("fecha", [$fecha_fin, $fecha_fin])->get();

        if ($servicio_id != 'todos') {
            $servicios = Servicio::where("id", $servicio_id)->get();
        }

        $pdf = PDF::loadView('reportes.inventario_equipos', compact('servicios'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('inventario_equipos.pdf');
    }

    public function servicios()
    {
        return Inertia::render("Reportes/Servicios");
    }

    public function r_servicios(Request $request)
    {
        $cliente_id =  $request->cliente_id;
        $tipo_servicio =  $request->tipo_servicio;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $servicios = Servicio::select("servicios.*");

        if ($cliente_id != 'todos') {
            $servicios->where("cliente_id", $cliente_id);
        }

        if ($tipo_servicio != 'todos') {
            $servicios->where("tipo_servicio", $tipo_servicio);
        }

        $servicios->whereBetween("fecha", [$fecha_ini, $fecha_fin]);

        $servicios = $servicios->get();

        $pdf = PDF::loadView('reportes.servicios', compact('servicios'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('servicios.pdf');
    }

    public function rg_servicios(Request $request)
    {
        $cliente_id =  $request->cliente_id;
        $tipo_servicio =  $request->tipo_servicio;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $tipo_servicios = ["GARANTÍA", "CONTRATO", "FACTURAR", "SOPORTE", "RELEVAMIENTO", "OTROS"];

        if ($tipo_servicio != 'todos') {
            $tipo_servicios = [$tipo_servicio];
        }

        $data = [];

        foreach ($tipo_servicios as $value) {
            $servicios = Servicio::select("servicios.*");

            if ($cliente_id != 'todos') {
                $servicios->where("cliente_id", $cliente_id);
            }

            $servicios->where("tipo_servicio", $value);

            $servicios->whereBetween("fecha", [$fecha_ini, $fecha_fin]);

            $servicios = $servicios->count();
            $data[] = [$value, (int)$servicios];
        }

        return response()->JSON([
            "data" => $data
        ]);
    }

    public function hora_servicios()
    {
        return Inertia::render("Reportes/HoraServicios");
    }

    public function r_hora_servicios(Request $request)
    {
        $servicio_id =  $request->servicio_id;
        $tipo_servicio =  $request->tipo_servicio;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $clientes = Cliente::where("status", 1)->get();

        if ($servicio_id != 'todos') {
            $clientes = Cliente::select("clientes.*")
                ->join("servicios", "servicios.cliente_id", "clientes.id")
                ->where("servicios.id", $servicio_id)
                ->where("clientes.status", 1)
                ->get();
        }

        $servicios_clientes = [];

        foreach ($clientes as $item) {
            $servicios = Servicio::select("servicios.*");
            $servicios->where("cliente_id", $item->id);
            if ($servicio_id != 'todos') {
                $servicios->where("id", $servicio_id);
            }
            if ($tipo_servicio != 'todos') {
                $servicios->where("tipo_servicio", $tipo_servicio);
            }
            $servicios->whereBetween("fecha", [$fecha_ini, $fecha_fin]);

            $servicios_clientes[$item->id] = $servicios->get();
        }

        $pdf = PDF::loadView('reportes.hora_servicios', compact('clientes', 'servicios_clientes'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('hora_servicios.pdf');
    }

    public function rg_hora_servicios(Request $request)
    {
        $servicio_id =  $request->servicio_id;
        $tipo_servicio =  $request->tipo_servicio;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;


        $clientes = Cliente::where("status", 1)->get();

        if ($servicio_id != 'todos') {
            $clientes = Cliente::select("clientes.*")
                ->join("servicios", "servicios.cliente_id", "clientes.id")
                ->where("servicios.id", $servicio_id)
                ->where("clientes.status", 1)
                ->get();
        }

        $servicios_clientes = [];

        $data = [];
        $categories = [];
        $tipo_servicios = ["GARANTÍA", "CONTRATO", "FACTURAR", "SOPORTE", "RELEVAMIENTO", "OTROS"];
        if ($tipo_servicio != 'todos') {
            $tipo_servicios = [$tipo_servicio];
        }

        foreach ($clientes as $item) {
            $categories[] = $item->razon_social;
        }

        foreach ($tipo_servicios as  $key => $value) {
            $data[] = [
                "name" => $value,
                "data" => []
            ];
            foreach ($clientes as $item) {
                $servicios = Servicio::select("servicios.*");
                $servicios->where("cliente_id", $item->id);
                $servicios->where("tipo_servicio", $value);
                if ($servicio_id != 'todos') {
                    $servicios->where("id", $servicio_id);
                }
                $servicios->whereBetween("fecha", [$fecha_ini, $fecha_fin]);

                $data[$key]["data"][] = (float)$servicios->sum("total_horas");
            }
        }

        return response()->JSON([
            "categories" => $categories,
            "data" => $data,
        ]);
    }

    public function solicitud_atencion()
    {
    }

    public function r_solicitud_atencion(Request $request)
    {
    }

    public function rg_solicitud_atencion(Request $request)
    {
    }

    public function personal()
    {
    }

    public function r_personal(Request $request)
    {
    }
}
