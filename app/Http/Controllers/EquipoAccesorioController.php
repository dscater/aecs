<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EquipoAccesorioController extends Controller
{
    public function index()
    {
        return Inertia::render("EquipoAccesorios/Index");
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $servicios = Servicio::with(["cliente", "personal"])->select("servicios.*")
            ->join("clientes", "clientes.id", "=", "servicios.cliente_id")
            ->join("personals", "personals.id", "=", "servicios.personal_id")
            ->where("servicios.status", 1);

        if (trim($search) != "") {
            $servicios->where(DB::raw("CONCAT(servicios.cod,' ',servicios.tipo,' ',servicios.marca,' ',servicios.modelo,' ',servicios.nro_serie,' ',servicios.nro_parte,' ',servicios.nro_activo,' ',servicios.ubicacion)"), "LIKE", "%$search%");
            // $servicios->where("razon_social", "LIKE", "%$search%");
        }

        if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
            $servicios->where("personal_id", Auth::user()->personal->id);
        }

        $servicios = $servicios->orderBy("id", "desc")->paginate($request->itemsPerPage);
        return response()->JSON([
            "servicios" => $servicios
        ]);
    }
}
