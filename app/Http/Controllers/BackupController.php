<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BackupController extends Controller
{
    public function index()
    {
        return Inertia::render("Backup/Index");
    }

    public function generarBackup()
    {
        // CONFIGURACIÓN
        $dbhost = "localhost";
        $dbname = config('database.connections.mysql.database');
        $dbuser = config('database.connections.mysql.username');
        $dbpass = config('database.connections.mysql.password');
        $path_mysqldump = "C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump"; // ruta de mysqldump
        $file_name = $dbname . '_' . date("d_m_Y_H_i_s") . '.sql';
        $dbfile = public_path() . "/backups/" . $file_name; // ruta donde se guarda el backup
        // FIN CONFIGURACIÓN

        try {
            //generar 
            $command = "$path_mysqldump -u$dbuser $dbname > $dbfile";
            if ($dbpass != "") {
                $command = "$path_mysqldump -u$dbuser -p$dbpass $dbname > $dbfile";
            }
            exec($command, $output, $worked);
            $message = "";
            switch ($worked) {
                case 0:
                    $message = 'La base de datos <b>' . $dbname . '</b> se ha almacenado correctamente en la siguiente ruta ' . getcwd() . '/' . $dbfile . '</b>';
                    break;
                case 1:
                    $message = 'Se ha producido un error al exportar <b>' . $dbname . '</b> a ' . getcwd() . '/' . $dbfile . '</b>';
                    break;
                case 2:
                    $message = 'Se ha producido un error de exportación, compruebe la siguiente información: <br/><br/><table><tr><td>Nombre de la base de datos:</td><td><b>' . $dbname . '</b></td></tr><tr><td>Nombre de usuario MySQL:</td><td><b>' . $dbuser . '</b></td></tr><tr><td>Contraseña MySQL:</td><td><b>NOTSHOWN</b></td></tr><tr><td>Nombre de host MySQL:</td><td><b>' . $dbhost . '</b></td></tr></table>';
                    break;
            }
            if ($worked == 0) {
                // return response()->download($dbfile)->deleteFileAfterSend(true);
                return response()->download(
                    $dbfile,
                    $file_name,
                    [
                        'Content-Disposition' => 'filename="' . $file_name . '"'
                    ]
                );
            }

            throw new Exception($message);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->JSON([
                "message" => "Ocurrió un error al intentar descargar la base de datos"
            ], 403);
        }
    }
}
