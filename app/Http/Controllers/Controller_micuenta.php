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

        $query = "SELECT * FROM parametrizaciones WHERE lista = 'ListaDeportesApuestas'  and nombre not in ('TITULO') order by valor";
        $deportes= DB::select($query);

        $query = "SELECT  apuestas.id,    CASE WHEN resultado = 0 THEN 'En curso' WHEN resultado = 1 THEN 'Ganada' WHEN resultado = 2 THEN 'Cerrada' WHEN resultado = 3 THEN 'Perdida' END AS estado_descri, id_usuario, parametrizaciones.nombre as deporte, porcentaje, dineroApostado, apuestas.descripcion, resultado, resultadoDinero, stack, probabilidad,cast(apuestas.created_at as date) as created_at FROM apuestas, parametrizaciones where lista = 'ListaDeportesApuestas' and parametrizaciones.valor = apuestas.deporte and resultado= 0  and ID_USUARIO =".$idUsu;
        $apuestas= DB::select($query);

        $query = "SELECT dineroEnApuestas, dineroStack FROM historico_apuestas WHERE usuario =".$idUsu;
        
        $sa=DB::select($query);
        foreach ($sa as $a){
            $Pdte =  $a->dineroEnApuestas;
            $total = $a->dineroStack;
        }
      

        $mensaje=$this->obtener_mensaje( $idUsu);

        
        return view('apuestas.abiertas')->with('mensaje',$mensaje)->with('pdte',$Pdte)->with('total',$total)->with('deportes',$deportes)->with('apuestas',$apuestas);
    }
    
    public function modificar(request $info){
    
     
        
        return redirect()->action('App\Http\Controllers\Controller_admin_apuestas@Abiertas');

        }
   
    
}
