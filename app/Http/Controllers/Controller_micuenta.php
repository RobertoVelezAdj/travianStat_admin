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

        $query = "SELECT * FROM parametrizaciones p WHERE lista ='Razas' and nombre <>'TITULO' and p.valor <4 ; ";
        $razas=DB::select($query);
        $mensaje=$this->obtener_mensaje( $idUsu);
        $query ="SELECT id, nombre FROM servidor s where s.id>0 and estado = 0;";
        $servidor=DB::select($query);
        
        return view('cuenta.index')->with('mensaje',$mensaje)->with('info',$cuenta)->with('raza',$razas)->with('servidor',$servidor);
    }
    
    public function modificar(request $info){
    
        $idUsu =auth()->id();
        $query ="UPDATE users SET raza=".$info->raza.",servidor=".$info->servidor.",nombre_cuenta='".$info->nombre."', id_telegram =".$info->telegram." WHERE  id =".$idUsu;
        $s=DB::select($query);
        

        $query ="SELECT nombre_cuenta,a.*, c.idCuenta FROM users u, cuenta_inac c, aldea_inac a, servidor s where a.IdCuenta = c.IdCuenta and a.id_server = c.IdServer and c.IdServer = u.servidor and c.NombreCuenta = u.nombre_cuenta and s.fch_mod = a.created_at and u.id = ".$idUsu;
        $resultado= DB::select($query);
        //si existe inserto las aldeas (y no las tengo)
        $id_aldea= 0;
        foreach ($resultado as $a){
            $contador =0;
            $query = "SELECT coord_X, coord_y FROM aldea WHERE coord_X = ".$a->coor_x." and coord_y =".$a->coor_y." and aldea.id_usuario = ".$idUsu;//cambiar quert
            $resultado=DB::select($query);
            
            foreach ($resultado as $a){
                $contador =  1;
            }
            if($contador<1){
                //si no la tentgo en bbdd insert ()
                $query = "INSERT INTO aldea(id_usuario,coord_x,coord_y,nombre,tipo,fiesta_pequena,fiesta_grande,created_at)VALUES(".$idUsu.",".$a->coor_x.",".$a->coor_y.",'".$a->NombreAldea."',8,0,0,current_timestamp())";
                $tipos= DB::select($query);
        
                $query = "SELECT max(id) as id FROM aldea WHERE id_usuario = ".$idUsu;
                $resultado=DB::select($query);
                foreach ($resultado as $a){
                    $id_aldea =  $a->id;
                }
                //con el id.. registro en edificios y produccion
                $query = "INSERT INTO aldea_edificios(id_aldea)VALUES(".$id_aldea.")";
                $tipos= DB::select($query);
                $query = "INSERT INTO aldea_tropas(id_aldea)VALUES(".$id_aldea.")";
                $tipos= DB::select($query);
                $query = "INSERT INTO aldea_producion(id_aldea)VALUES(".$id_aldea.")";
                $tipos= DB::select($query);
            }
                
        }
        return redirect()->action('App\Http\Controllers\Controller_micuenta@index');
        }
   
    
}
