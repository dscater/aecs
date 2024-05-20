<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PersonalController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
        "paterno" => "required|min:1",
        "ci" => "required|min:1",
        "ci_exp" => "required",
        "estado_civil" => "required",
        "fecha_nac" => "required",
        "especialidad" => "required",
        "cel" => "required|min:1",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
        "paterno.required" => "Este campo es obligatorio",
        "paterno.min" => "Debes ingresar al menos :min caracteres",
        "ci.required" => "Este campo es obligatorio",
        "ci.unique" => "Este C.I. ya fue registrado",
        "ci.min" => "Debes ingresar al menos :min caracteres",
        "ci_exp.required" => "Este campo es obligatorio",
        "estado_civil.required" => "Este campo es obligatorio",
        "fecha_nac.required" => "Este campo es obligatorio",
        "especialidad.required" => "Este campo es obligatorio",
        "cel.required" => "Este campo es obligatorio",
        "cel.min" => "Debes ingresar al menos :min caracteres",
    ];

    public function index()
    {
        return Inertia::render("Personals/Index");
    }

    public function listado(Request $request)
    {
        $personals = Personal::select("personals.*")->where("status", 1);

        if ($request->sin_usuario) {
            if ($request->id && $request->id != '') {
                $personals = $personals->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('users')
                        ->whereRaw('users.personal_id = personals.id');
                })->orWhere(function ($subquery) use ($request) {
                    $subquery->whereIn('personals.id', [$request->id]);
                });
            } else {
                $personals = $personals->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('users')
                        ->whereRaw('users.personal_id = personals.id');
                });
            }
        }

        $personals = $personals->get();
        return response()->JSON([
            "personals" => $personals
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $personals = Personal::select("personals.*")->where("status", 1);

        if (trim($search) != "") {
            $personals->where("nombre", "LIKE", "%$search%");
            $personals->orWhere("paterno", "LIKE", "%$search%");
            $personals->orWhere("materno", "LIKE", "%$search%");
            $personals->orWhere("ci", "LIKE", "%$search%");
        }

        $personals = $personals->paginate($request->itemsPerPage);
        return response()->JSON([
            "personals" => $personals
        ]);
    }

    public function store(Request $request)
    {
        $this->validacion['ci'] = 'required|min:4|numeric|unique:personals,ci';
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:2048';
        }
        $request->validate($this->validacion, $this->mensajes);

        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Personal
            $nuevo_personal = Personal::create(array_map('mb_strtoupper', $request->except('foto', 'hoja_vida')));
            if ($request->hasFile('foto')) {
                $file = $request->foto;
                $nom_foto = time() . '_' . $nuevo_personal->id . '.' . $file->getClientOriginalExtension();
                $nuevo_personal->foto = $nom_foto;
                $file->move(public_path() . '/imgs/personals/', $nom_foto);
            }
            if ($request->hasFile('hoja_vida')) {
                $file = $request->hoja_vida;
                $nom_hoja_vida = time() . '_' . $nuevo_personal->id . '.' . $file->getClientOriginalExtension();
                $nuevo_personal->hoja_vida = $nom_hoja_vida;
                $file->move(public_path() . '/files/', $nom_hoja_vida);
            }
            $nuevo_personal->save();

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_personal, "personals");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN PERSONAL TÉCNICO',
                'datos_original' => $datos_original,
                'modulo' => 'PERSONAL TÉCNICO',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("personals.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Personal $personal)
    {
    }

    public function update(Personal $personal, Request $request)
    {
        $this->validacion['ci'] = 'required|min:4|numeric|unique:personals,ci,' . $personal->id;
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:2048';
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($personal, "personals");
            $personal->update(array_map('mb_strtoupper', $request->except('foto', 'hoja_vida')));
            if ($request->hasFile('foto')) {
                $antiguo = $personal->foto;
                if ($antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/personals/' . $antiguo);
                }
                $file = $request->foto;
                $nom_foto = time() . '_' . $personal->id . '.' . $file->getClientOriginalExtension();
                $personal->foto = $nom_foto;
                $file->move(public_path() . '/imgs/personals/', $nom_foto);
            }
            if ($request->hasFile('hoja_vida')) {
                $antiguo = $personal->hoja_vida;
                if ($antiguo) {
                    \File::delete(public_path() . '/files/' . $antiguo);
                }
                $file = $request->hoja_vida;
                $nom_hoja_vida = time() . '_' . $personal->id . '.' . $file->getClientOriginalExtension();
                $personal->hoja_vida = $nom_hoja_vida;
                $file->move(public_path() . '/files/', $nom_hoja_vida);
            }
            $personal->save();

            $datos_nuevo = HistorialAccion::getDetalleRegistro($personal, "personals");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN PERSONAL TÉCNICO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'PERSONAL TÉCNICO',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);


            DB::commit();
            return redirect()->route("personals.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Personal $personal)
    {
        DB::beginTransaction();
        try {
            // $antiguo = $personal->foto;
            // if ($antiguo != 'default.png') {
            //     \File::delete(public_path() . '/imgs/personals/' . $antiguo);
            // }
            // $antiguo = $personal->hoja_vida;
            // if ($antiguo) {
            //     \File::delete(public_path() . '/files/' . $antiguo);
            // }
            $datos_original = HistorialAccion::getDetalleRegistro($personal, "personals");
            $personal->status = 0;
            $personal->save();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN PERSONAL TÉCNICO',
                'datos_original' => $datos_original,
                'modulo' => 'PERSONAL TÉCNICO',
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
