<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "paterno",
        "materno",
        "ci",
        "ci_exp",
        "estado_civil",
        "fecha_nac",
        "cel",
        "domicilio",
        "especialidad",
        "record",
        "hoja_vida",
        "foto",
        "fecha_registro",
        "status",
    ];

    protected $appends = ["fecha_registro_t", "full_name", "iniciales_nombre", "full_ci", "url_foto", "fecha_nac_t", "url_hoja_vida"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getFullNameAttribute()
    {
        return $this->nombre . ' ' . $this->paterno . ($this->materno != NULL && $this->materno != '' ? ' ' . $this->materno : '');
    }

    public function getFullCiAttribute()
    {
        return $this->ci . ' ' . $this->ci_exp;
    }

    public function getUrlFotoAttribute()
    {
        if ($this->foto) {
            return asset("imgs/personals/" . $this->foto);
        }
        return null;
    }

    public function getUrlHojaVidaAttribute()
    {
        if ($this->hoja_vida) {
            return asset("files/" . $this->hoja_vida);
        }
        return null;
    }

    public function getInicialesNombreAttribute()
    {
        $iniciales = substr($this->nombre, 0, 1) . substr($this->paterno, 0, 1);
        return mb_strtoupper($iniciales);
    }
    public function getFechaNacTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_nac));
    }

    // RELACIONES
}
