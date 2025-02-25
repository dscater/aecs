<?php

use App\Http\Controllers\AvanceObraController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\EquipoAccesorioController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\SolicitudAtencionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
});

Route::get("configuracions/getConfiguracion", [ConfiguracionController::class, 'getConfiguracion'])->name("configuracions.getConfiguracion");

Route::middleware('auth')->group(function () {
    // INICIO
    Route::get('/inicio', [InicioController::class, 'inicio'])->name('inicio');
    Route::get("/inicio/getMaximoImagenes", [InicioController::class, 'getMaximoImagenes'])->name("entrenamientos.getMaximoImagenes");

    Route::get('/graf1', [InicioController::class, 'graf1'])->name('graf1');
    Route::get('/graf2', [InicioController::class, 'graf2'])->name('graf2');
    Route::get('/graf3', [InicioController::class, 'graf3'])->name('graf3');
    Route::get('/graf4', [InicioController::class, 'graf4'])->name('graf4');

    // INSTITUCION
    Route::resource("configuracions", ConfiguracionController::class)->only(
        ["index", "show", "update"]
    );

    // USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("/getUser", [UserController::class, 'getUser'])->name('users.getUser');
    Route::get("/permisos", [UserController::class, 'permisos']);
    Route::get("/menu_user", [UserController::class, 'permisos']);

    // USUARIOS
    Route::put("/usuarios/password/{user}", [UsuarioController::class, 'actualizaPassword'])->name("usuarios.password");
    Route::get("/usuarios/paginado", [UsuarioController::class, 'paginado'])->name("usuarios.paginado");
    Route::get("/usuarios/listado", [UsuarioController::class, 'listado'])->name("usuarios.listado");
    Route::get("/usuarios/listado/byTipo", [UsuarioController::class, 'byTipo'])->name("usuarios.byTipo");
    Route::get("/usuarios/show/{user}", [UsuarioController::class, 'show'])->name("usuarios.show");
    Route::put("/usuarios/update/{user}", [UsuarioController::class, 'update'])->name("usuarios.update");
    Route::delete("/usuarios/{user}", [UsuarioController::class, 'destroy'])->name("usuarios.destroy");
    Route::resource("usuarios", UsuarioController::class)->only(
        ["index", "store"]
    );

    // PERSONAL
    Route::get("/personals/paginado", [PersonalController::class, 'paginado'])->name("personals.paginado");
    Route::get("/personals/listado", [PersonalController::class, 'listado'])->name("personals.listado");
    Route::resource("personals", PersonalController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // CLIENTES
    Route::get("/clientes/paginado", [ClienteController::class, 'paginado'])->name("clientes.paginado");
    Route::get("/clientes/listado", [ClienteController::class, 'listado'])->name("clientes.listado");
    Route::resource("clientes", ClienteController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // SOLICITUD DE ANTENCIÓN
    Route::put("/solicitud_atencions/update_estado/{solicitud_atencion}", [SolicitudAtencionController::class, 'update_estado'])->name("solicitud_atencions.update_estado");
    Route::get("/solicitud_atencions/paginado", [SolicitudAtencionController::class, 'paginado'])->name("solicitud_atencions.paginado");
    Route::get("/solicitud_atencions/listado", [SolicitudAtencionController::class, 'listado'])->name("solicitud_atencions.listado");
    Route::resource("solicitud_atencions", SolicitudAtencionController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // SERVICIOS
    Route::get("/servicios/paginado", [ServicioController::class, 'paginado'])->name("servicios.paginado");
    Route::get("/servicios/listado", [ServicioController::class, 'listado'])->name("servicios.listado");
    Route::resource("servicios", ServicioController::class)->only(
        ["index", "create", "store", "edit", "update", "show", "destroy"]
    );

    // EQUIPOS ACCESORIOS
    Route::get("/equipo_accesorios", [EquipoAccesorioController::class, 'index'])->name("equipo_accesorios.index");
    Route::get("/equipo_accesorios/paginado", [EquipoAccesorioController::class, 'paginado'])->name("equipo_accesorios.paginado");

    // BACKUP
    Route::get("backup", [BackupController::class, 'index'])->name("backup.index");
    Route::post("backup/generarBackup", [BackupController::class, 'generarBackup'])->name("backup.generarBackup");

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");

    Route::get('reportes/inventario_equipos', [ReporteController::class, 'inventario_equipos'])->name("reportes.inventario_equipos");
    Route::get('reportes/r_inventario_equipos', [ReporteController::class, 'r_inventario_equipos'])->name("reportes.r_inventario_equipos");

    Route::get('reportes/servicios', [ReporteController::class, 'servicios'])->name("reportes.servicios");
    Route::get('reportes/r_servicios', [ReporteController::class, 'r_servicios'])->name("reportes.r_servicios");
    Route::get('reportes/rg_servicios', [ReporteController::class, 'rg_servicios'])->name("reportes.rg_servicios");

    Route::get('reportes/hora_servicios', [ReporteController::class, 'hora_servicios'])->name("reportes.hora_servicios");
    Route::get('reportes/r_hora_servicios', [ReporteController::class, 'r_hora_servicios'])->name("reportes.r_hora_servicios");
    Route::get('reportes/rg_hora_servicios', [ReporteController::class, 'rg_hora_servicios'])->name("reportes.rg_hora_servicios");

    Route::get('reportes/solicitud_atencion', [ReporteController::class, 'solicitud_atencion'])->name("reportes.solicitud_atencion");
    Route::get('reportes/r_solicitud_atencion', [ReporteController::class, 'r_solicitud_atencion'])->name("reportes.r_solicitud_atencion");
    Route::get('reportes/rg_solicitud_atencion', [ReporteController::class, 'rg_solicitud_atencion'])->name("reportes.rg_solicitud_atencion");

    Route::get('reportes/personal', [ReporteController::class, 'personal'])->name("reportes.personal");
    Route::get('reportes/r_personal', [ReporteController::class, 'r_personal'])->name("reportes.r_personal");
});

require __DIR__ . '/auth.php';
