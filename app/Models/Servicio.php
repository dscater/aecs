<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        "cod",
        "nro",
        "cliente_id",
        "personal_id",
        "fecha",
        "hora_ini",
        "hora_fin",
        "total_horas",
        "ubicacion",
        "tipo",
        "marca",
        "modelo",
        "nro_serie",
        "nro_parte",
        "nro_activo",
        "tipo_servicio",
        "foto",
        "problema",
        "trabajo_realizado",
        "partes",
        "tipo_trabajo",
        "fecha_registro",
        "status",
    ];

    protected $appends = ["fecha_registro_t", "fecha_t", "url_foto", "nom_equipo"];

    public function getNomEquipoAttribute()
    {
        $nom_equipo = $this->cod;

        if ($this->tipo) {
            $nom_equipo .= ' | ' . $this->tipo;
        }

        if ($this->marca) {
            $nom_equipo .= ' | ' . $this->marca;
        }

        if ($this->modelo) {
            $nom_equipo .= ' | ' . $this->modelo;
        }

        if ($this->nro_serie) {
            $nom_equipo .= ' | ' . $this->nro_serie;
        }

        return $nom_equipo;
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getFechaTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getUrlFotoAttribute()
    {
        if ($this->foto) {
            return asset('imgs/equipos/' . $this->foto);
        }
        return '';
    }

    // relaciones

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    // funciones
    public static function obtenerNuevoCodigo()
    {
        $ultimo = Servicio::orderBy("id", "desc")->get()->first();
        $nro = 1;
        if ($ultimo) {
            $nro = (int)$ultimo->nro + 1;
        }
        $codigo = "SER." . $nro;

        return [$codigo, $nro];
    }
}
