<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
     
    public function creacion_mensaje($tipo, $mensaje,$usuario){
      
        $query2 = "INSERT INTO notificaciones_emergentes(usuario,tipo,mensaje,estado,created_at)values(".$usuario.",'".$tipo."','".$mensaje."','0',current_date())";
        $aux2= DB::select($query2);
    }
    public function obtener_mensaje($usuario){
        $mensaje="";
        $query = "SELECT * FROM notificaciones_emergentes WHERE  estado = '0' and usuario = ".$usuario;
        $aux= DB::select($query);
        foreach ($aux as $a){
            $mensaje = "Toast.fire({
                icon: '".$a->tipo."',
                title: '".$a->mensaje."'
                });";   
                $query2 = "UPDATE notificaciones_emergentes SET estado='1' WHERE id =  ".$a->id;
                $aux2= DB::select($query2);
         }
         
        return $mensaje;
    }
    public function paso_mensaje($tipo, $mensaje,$usuario){
      
        $query2 = "INSERT INTO notificaciones_emergentes(usuario,tipo,mensaje,estado,created_at)values(".$usuario.",'".$tipo."','".$mensaje."','2',current_date())";
        $aux2= DB::select($query2);
    }
    public function obtener_lista($usuario){
        $lista="";
        $query = "SELECT * FROM notificaciones_emergentes WHERE  estado = '2' and usuario = ".$usuario;
        $aux= DB::select($query);
        foreach ($aux as $a){
            $lista = $a->tipo;   
            $query2 = "UPDATE notificaciones_emergentes SET estado='1' WHERE id =  ".$a->id;
            $aux2= DB::select($query2);
         }
        return $lista;
    }
}
