<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ServicioController extends Controller
{
    public $validacion = [
        "cliente_id" => "required",
        "personal_id" => "required",
        "fecha" => "required|date",
        "hora_ini" => "required",
        "hora_fin" => "required",
        "total_horas" => "required",
        "ubicacion" => "required|min:3",
        "tipo_servicio" => "required",
        "problema" => "required",
        "trabajo_realizado" => "required",
        "tipo_trabajo" => "required",
    ];

    public $mensajes = [
        "cliente_id.required" => "Este campo es obligatorio",
        "personal_id.required" => "Este campo es obligatorio",
        "descripcion.required" => "Este campo es obligatorio",
        "fecha.required" => "Este campo es obligatorio",
        "hora_ini.required" => "Este campo es obligatorio",
        "hora_fin.required" => "Este campo es obligatorio",
        "total_horas.required" => "Este campo es obligatorio",
        "ubicacion.required" => "Este campo es obligatorio",
        "ubicacion.min" => "Debes ingresar al menos :min caracteres",
        "tipo_servicio.required" => "Este campo es obligatorio",
        "problema.required" => "Este campo es obligatorio",
        "trabajo_realizado.required" => "Este campo es obligatorio",
        "tipo_trabajo.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Servicios/Index");
    }

    public function listado(Request $request)
    {
        $servicios = Servicio::select("servicios.*")->where("status", 1);

        if (isset($request->order) && $request->order) {
            $servicios->orderBy("id", "desc");
        }

        if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
            $servicios->where("servicios.personal_id", Auth::user()->personal->id);
        }

        $servicios = $servicios->get();
        return response()->JSON([
            "servicios" => $servicios
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $servicios = Servicio::with(["cliente", "personal"])->select("servicios.*")
            ->join("clientes", "clientes.id", "=", "servicios.cliente_id")
            ->join("personals", "personals.id", "=", "servicios.personal_id")
            ->where("servicios.status", 1);

        if (trim($search) != "") {
            $servicios->where(DB::raw("CONCAT(clientes.razon_social,' ',personals.nombre,' ',personals.paterno,' ',personals.materno,' ',servicios.cod)"), "LIKE", "%$search%");
            // $servicios->where("razon_social", "LIKE", "%$search%");
        }
        if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
            $servicios->where("servicios.personal_id", Auth::user()->personal->id);
        }

        $servicios = $servicios->orderBy("id", "desc")->paginate($request->itemsPerPage);
        return response()->JSON([
            "servicios" => $servicios
        ]);
    }

    public function create()
    {
        return Inertia::render("Servicios/Create");
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            $a_nuevo_cod = Servicio::obtenerNuevoCodigo();
            $request["cod"] = $a_nuevo_cod[0];
            $request["nro"] = $a_nuevo_cod[1];
            // crear el Servicio
            $nuevo_servicio = Servicio::create(array_map('mb_strtoupper', $request->except("foto")));
            if ($request->hasFile('foto')) {
                $file = $request->foto;
                $nom_foto = time() . '_' . $nuevo_servicio->id . '.' . $file->getClientOriginalExtension();
                $nuevo_servicio->foto = $nom_foto;
                $file->move(public_path() . '/imgs/equipos/', $nom_foto);
            }
            $nuevo_servicio->save();

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_servicio, "servicios");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UNA SERVICIO',
                'datos_original' => $datos_original,
                'modulo' => 'SERVICIOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("servicios.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function edit(Servicio $servicio)
    {

        $servicio = $servicio->load(["cliente", "personal"]);
        return Inertia::render("Servicios/Edit", compact("servicio"));
    }

    public function show(Servicio $servicio)
    {
    }

    public function update(Servicio $servicio, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($servicio, "servicios");
            $servicio->update(array_map('mb_strtoupper', $request->except("foto")));
            if ($request->hasFile('foto')) {
                $antiguo = $servicio->foto;
                if ($antiguo && $antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/equipos/' . $antiguo);
                }
                $file = $request->foto;
                $nom_foto = time() . '_' . $servicio->id . '.' . $file->getClientOriginalExtension();
                $servicio->foto = $nom_foto;
                $file->move(public_path() . '/imgs/equipos/', $nom_foto);
            }
            $servicio->save();
            $datos_nuevo = HistorialAccion::getDetalleRegistro($servicio, "servicios");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UNA SERVICIO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'SERVICIOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);


            DB::commit();
            return redirect()->route("servicios.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Servicio $servicio)
    {
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($servicio, "servicios");
            $servicio->status = 0;
            $servicio->save();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UNA SERVICIO',
                'datos_original' => $datos_original,
                'modulo' => 'SERVICIOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'message' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
