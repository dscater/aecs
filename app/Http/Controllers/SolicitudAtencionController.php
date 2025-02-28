<?php

namespace App\Http\Controllers;

use App\Mail\SolicitudMail;
use App\Models\Configuracion;
use App\Models\HistorialAccion;
use App\Models\SolicitudAtencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SolicitudAtencionController extends Controller
{
    public $validacion = [
        "cliente_id" => "required",
        "personal_id" => "required",
        "descripcion" => "required|min:3",
        "fecha" => "required|date",
        "hora" => "required",
    ];

    public $mensajes = [
        "cliente_id.required" => "Este campo es obligatorio",
        "personal_id.required" => "Este campo es obligatorio",
        "descripcion.required" => "Este campo es obligatorio",
        "descripcion.min" => "Debes ingresar al menos :min caracteres",
        "fecha.required" => "Este campo es obligatorio",
        "hora.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("SolicitudAtencions/Index");
    }

    public function listado(Request $request)
    {
        $solicitud_atencions = SolicitudAtencion::select("solicitud_atencions.*")->where("status", 1);
        if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
            $solicitud_atencions->where("personal_id", Auth::user()->personal->id);
        }
        $solicitud_atencions = $solicitud_atencions->get();
        return response()->JSON([
            "solicitud_atencions" => $solicitud_atencions
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $solicitud_atencions = SolicitudAtencion::with(["cliente", "personal"])->select("solicitud_atencions.*")
            ->join("clientes", "clientes.id", "=", "solicitud_atencions.cliente_id")
            ->join("personals", "personals.id", "=", "solicitud_atencions.personal_id")
            ->where("solicitud_atencions.status", 1);

        if (trim($search) != "") {
            $solicitud_atencions->where(DB::raw("CONCAT(clientes.razon_social,' ',personals.nombre,' ',personals.paterno,' ',personals.materno,' ',solicitud_atencions.descripcion)"), "LIKE", "%$search%");
            // $solicitud_atencions->where("razon_social", "LIKE", "%$search%");
        }
        if (Auth::user()->tipo != 'GERENTE TÉCNICO') {
            $solicitud_atencions->where("solicitud_atencions.personal_id", Auth::user()->personal->id);
        }

        $solicitud_atencions = $solicitud_atencions->paginate($request->itemsPerPage);
        return response()->JSON([
            "solicitud_atencions" => $solicitud_atencions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el SolicitudAtencion
            $nuevo_solicitud_atencion = SolicitudAtencion::create(array_map('mb_strtoupper', $request->all()));

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_solicitud_atencion, "solicitud_atencions");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UNA SOLICITUD DE ATENCIÓN',
                'datos_original' => $datos_original,
                'modulo' => 'SOLICITUD DE ATENCIÓN',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);
            // enviar correo
            self::enviarCorreo($nuevo_solicitud_atencion->cliente, $nuevo_solicitud_atencion, "PENDIENTE");

            DB::commit();
            return redirect()->route("solicitud_atencions.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(SolicitudAtencion $solicitud_atencion) {}

    public function update(SolicitudAtencion $solicitud_atencion, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($solicitud_atencion, "solicitud_atencions");
            $solicitud_atencion->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($solicitud_atencion, "solicitud_atencions");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UNA SOLICITUD DE ATENCIÓN',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'SOLICITUD DE ATENCIÓN',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);


            DB::commit();
            return redirect()->route("solicitud_atencions.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function update_estado(SolicitudAtencion $solicitud_atencion, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $old_estado = $solicitud_atencion->estado;
            $datos_original = HistorialAccion::getDetalleRegistro($solicitud_atencion, "solicitud_atencions");
            $solicitud_atencion->update([
                "estado" => $request->estado
            ]);

            $datos_nuevo = HistorialAccion::getDetalleRegistro($solicitud_atencion, "solicitud_atencions");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ EL ESTADO DE UNA SOLICITUD DE ATENCIÓN',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'SOLICITUD DE ATENCIÓN',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            if ($old_estado != $solicitud_atencion->estado) {
                // enviar correo
                self::enviarCorreo($solicitud_atencion->cliente, $solicitud_atencion, $solicitud_atencion->estado);
            }

            DB::commit();
            return redirect()->route("solicitud_atencions.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(SolicitudAtencion $solicitud_atencion)
    {
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($solicitud_atencion, "solicitud_atencions");
            $solicitud_atencion->status = 0;
            $solicitud_atencion->save();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UNA SOLICITUD DE ATENCIÓN',
                'datos_original' => $datos_original,
                'modulo' => 'SOLICITUD DE ATENCIÓN',
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

    public static function enviarCorreo($cliente, $solicitud_atencion, $estado)
    {
        if ($cliente->correo && trim($cliente->correo)) {
            // CONFIGURACION
            $configuracion = Configuracion::first();
            Config::set(
                [
                    'mail.mailers.default' => 'smtp',
                    'mail.mailers.smtp.host' => 'smtp.hostinger.com',
                    'mail.mailers.smtp.port' => '587',
                    'mail.mailers.smtp.encryption' => 'tls',
                    'mail.mailers.smtp.username' => 'mensaje@compuelectrica.com.bo',
                    'mail.mailers.smtp.password' => '1Uno2Dos3Tres-0',
                    'mail.from.address' => 'mensaje@compuelectrica.com.bo',
                    'mail.from.name' => $configuracion ? $configuracion->alias : 'AESC',
                ]
            );
            // FIN CONFIGURACION

            $datos = [
                "estado" => $estado,
                "cliente" => $cliente,
                "solicitud_atencion" => $solicitud_atencion,
            ];

            Mail::to($cliente->correo)
                ->send(new SolicitudMail($datos));
        }
    }
}
