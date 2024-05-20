<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "usuario",
        "password",
        "personal_id",
        "tipo",
        "foto",
        "acceso",
        "fecha_registro",
    ];


    protected $appends = ["permisos", "url_foto", "full_ci", "full_name", "iniciales_nombre", "fecha_registro_t"];


    protected $username = "usuario";

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_registro' => 'datetime',
        'password' => 'hashed',
    ];

    public function getPermisosAttribute()
    {
        return UserController::getPermisosUser();
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getFullCiAttribute()
    {
        return $this->ci . ' ' . $this->ci_exp;
    }

    public function getFullNameAttribute()
    {
        if ($this->personal) {
            return $this->nombre . ' ' . $this->paterno . ($this->materno != NULL && $this->materno != '' ? ' ' . $this->materno : '');
        }
        return $this->usuario;
    }

    public function getUrlFotoAttribute()
    {
        if ($this->foto) {
            return asset("imgs/personals/" . $this->foto);
        }
        return null;
    }

    public function getInicialesNombreAttribute()
    {
        if ($this->personal) {
            $iniciales = substr($this->nombre, 0, 1) . substr($this->paterno, 0, 1);
            return $iniciales;
        }
        $iniciales = substr($this->usuario, 0, 1);
        return mb_strtoupper($iniciales);
    }

    // FUNCIONES
    public static function getNombreUsuario($nom, $apep)
    {
        //determinando el nombre de usuario inicial del 1er_nombre+apep+tipoUser
        $nombre_user = substr(mb_strtoupper($nom), 0, 1); //inicial 1er_nombre
        $nombre_user .= mb_strtoupper($apep);

        return $nombre_user;
    }

    // RELACIONES
    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}
