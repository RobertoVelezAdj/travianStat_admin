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
 class Controller_micuenta extends Controller
{
    
    public function index(){   
         $idUsu =auth()->id();

        $query = "SELECT  * FROM users WHERE id =".$idUsu;
        $cuenta=DB::select($query);

        $query = "SELECT * FROM parametrizaciones WHERE lista ='Razas' and nombre <>'TITULO' and p.valor <3; ";
        $razas=DB::select($query);
        $mensaje=$this->obtener_mensaje( $idUsu);
        $query ="SELECT id, nombre FROM servidor and id>0";
        $servidor=DB::select($query);
        
        return view('cuenta.index')->with('mensaje',$mensaje)->with('info',$cuenta)->with('raza',$razas)->with('servidor',$servidor);
    }
    
    public function modificar(request $info){
    
        $idUsu =auth()->id();
        $query ="UPDATE users SET raza=".$info->raza.",servidor=".$info->servidor.",nombre_cuenta='".$info->nombre."', id_telegram =".$info->telegram." WHERE  id =".$idUsu;
        $s=DB::select($query);
        
        return redirect()->action('App\Http\Controllers\Controller_micuenta@index');

        }
   
    
}
