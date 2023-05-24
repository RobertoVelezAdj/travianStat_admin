<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use App\HTTP\Controllers\funciones\funciones;
use Illuminate\Support\Facades\DB;
 class Controller_admin_servidor extends Controller
{
    
    public function index(){   
         $idUsu =auth()->id();

        $query = "SELECT * FROM servidor order by id desc; ";
        $servidores= DB::select($query);
        $query = "SELECT velocidad FROM velocidad_servidores ";
        $velocidad= DB::select($query);

        $mensaje=$this->obtener_mensaje( $idUsu);
        return view('servidores.index')->with('mensaje',$mensaje)->with('servidores',$servidores)->with('velocidades',$velocidad);
    }
    
    public function crear(request $info){

        $idUsu =auth()->id();

        $query = "INSERT INTO servidor(nombre,ruta,ruta_inac,velocidad,estado,fch_creac,created_at,updated_at,fch_mod) VALUES ('".$info->nombre."','".$info->ruta."','".$info->ruta_inac."','".$info->velocidad."','0',current_date(),current_date(),current_date(),current_date())";
        $servidores= DB::select($query);

        $aux=$this->creacion_mensaje('success', "Servidor creado correctamente",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_admin_servidor@index');
        }
    public function borrar(request $info)
    {
        $idUsu =auth()->id();

        $query = "DELETE FROM `servidor` WHERE id = ".$info->id;
        $servidores= DB::select($query);

        $aux=$this->creacion_mensaje('success', "Servidor creado correctamente",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_admin_servidor@index');

    }
    
    
}
