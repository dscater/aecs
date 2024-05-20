<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAtencion extends Model
{
    use HasFactory;

    protected $fillable = [
        "cliente_id",
        "personal_id",
        "descripcion",
        "fecha",
        "hora",
        "estado",
        "fecha_registro",
        "status",
    ];

    protected $appends = ["fecha_registro_t", "fecha_hora_t"];

    public function getFechaRegistroTAttribute()
    {
        if ($this->fecha_registro) {
            return date("d/m/Y", strtotime($this->fecha_registro));
        }
        return "";
    }

    public function getFechaHoraTAttribute()
    {
        if ($this->fecha && $this->hora) {
            return date("d/m/Y H:i", strtotime($this->fecha . ' ' . $this->hora));
        }
        return "";
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
}
