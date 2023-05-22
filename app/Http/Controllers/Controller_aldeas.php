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
 class Controller_aldeas extends Controller
{
    
    public function index(){   
         $idUsu =auth()->id();
        //se elee información de las aldeas 
        $query = "SELECT * FROM parametrizaciones WHERE lista = 'TiposAldea'  and nombre not in ('TITULO') order by valor";
        $tipos= DB::select($query);
        $query = "SELECT a.id as id_aldea, a.coord_x, a.coord_y,a.nombre,a.tipo,a.fiesta_pequena, a.fiesta_grande, ap.madera, ap.barro, ap.hierro, ap.cereal,ap.puntos_cultura FROM aldea a,aldea_producion ap WHERE  ap.id_aldea = a.id and  a.id_usuario = ".$idUsu;
        $aldeas= DB::select($query);
  

        $mensaje=$this->obtener_mensaje( $idUsu);

        
        return view('aldea.index')->with('mensaje',$mensaje)->with('tipos',$tipos)->with('aldeas',$aldeas);
    }
    
    public function Crear(request $info){
        $idUsu =auth()->id();
        //registro en aldeas
        
        $query = "INSERT INTO aldea(id_usuario,coord_x,coord_y,nombre,tipo,fiesta_pequena,fiesta_grande,created_at)VALUES(".$idUsu.",".$info->coor_x.",".$info->coor_y.",'".$info->nombre."',".$info->tipoaldea.",".$info->f_pequena.",".$info->f_grande.",current_timestamp())";
        $tipos= DB::select($query);

        $query = "SELECT max(id) as id FROM aldea WHERE id_usuario = ".$idUsu;
        $resultado=DB::select($query);
        foreach ($resultado as $a){
            $id_aldea =  $a->id;
        }
        //con el id.. registro en edificios y produccion
        $query = "INSERT INTO aldea_edificios(id_aldea)VALUES(".$id_aldea.")";
        $tipos= DB::select($query);
        $query = "INSERT INTO aldea_producion(id_aldea)VALUES(".$id_aldea.")";
        $tipos= DB::select($query);

        $aux=$this->creacion_mensaje('success', "Aldea generada de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@index');
    }
    public function FinalizarApuesta(request $info) {
        $idUsu =auth()->id();
        $query = "SELECT dineroEnApuestas, dineroStack FROM historico_apuestas WHERE usuario =".$idUsu;
        $sa=DB::select($query);
        
        foreach ($sa as $a){
            $Pdte =  $a->dineroEnApuestas;
            $total = $a->dineroStack;
        }

        $query = "SELECT dineroApostado*-1 as resultado FROM apuestas WHERE id = ".$info->idapuesta;
            $sa=DB::select($query);
            foreach ($sa as $a){
                $resultado_din =  $a->resultado;
            }             
            $Pdte = $Pdte+$resultado_din;
        if($info->resultado<3){
            //Ganada o cierre
            $total= $total+round($info->cierre,2);
            $resultado_din = round($info->cierre,2)+$resultado_din;
        }
       
        $query = "UPDATE apuestas SET resultado = ".$info->resultado.", resultadodinero= ".$resultado_din." WHERE id = ".$info->idapuesta;
        $sa=DB::select($query);
        $query = "UPDATE historico_apuestas SET dineroStack = ".$total.",  dineroEnApuestas = ".$Pdte." WHERE USUARIO = ".$idUsu;
        $sa=DB::select($query);

        return redirect()->action('App\Http\Controllers\Controller_admin_apuestas@Abiertas');

        }
    public function cerradas(request $info){
        $idUsu =auth()->id();

        $query = "SELECT * FROM parametrizaciones WHERE lista = 'ListaDeportesApuestas'  and nombre not in ('TITULO') order by valor";
        $deportes= DB::select($query);

        $query = "SELECT  apuestas.id, resultadodinero,   CASE WHEN resultado = 0 THEN 'En curso' WHEN resultado = 1 THEN 'Ganada' WHEN resultado = 2 THEN 'Cerrada' WHEN resultado = 3 THEN 'Perdida' END AS estado_descri, id_usuario, parametrizaciones.nombre as deporte, porcentaje, dineroApostado, apuestas.descripcion, resultado, resultadoDinero, stack, probabilidad,cast(apuestas.created_at as date) as created_at FROM apuestas, parametrizaciones where lista = 'ListaDeportesApuestas' and parametrizaciones.valor = apuestas.deporte and resultado> 0  and ID_USUARIO =".$idUsu;
        $apuestas= DB::select($query);

      

        $mensaje=$this->obtener_mensaje( $idUsu);

        
        return view('apuestas.cerradas')->with('mensaje',$mensaje)->with('deportes',$deportes)->with('apuestas',$apuestas);    
        
        }
    public function historico(request $info)
    {
        $idUsu =auth()->id();
    
        $query = "select created_at as fecha, cast(apuestas.created_at as date)  as fecha2, sum(resultadodinero) as resultadodinero , (select count(*)from apuestas a where resultado > 0 and apuestas.created_at = a.created_at and id_usuario =".$idUsu.") as contador from apuestas where resultado> 0 and id_usuario =".$idUsu." group by fecha order by fecha desc";
        $cerradas=DB::select($query);
        $query = "select parametrizaciones.nombre as  deporte, sum(resultadodinero) as resultadodinero, (select count(*)from apuestas a where resultado > 0 and apuestas.deporte = a.deporte and apuestas.deporte = parametrizaciones.valor and parametrizaciones.lista =  'deporteApuestas' and id_usuario =".$idUsu.")  as contador from apuestas, parametrizaciones where apuestas.deporte = parametrizaciones.valor and parametrizaciones.lista =  'ListaDeportesApuestas' and resultado> 0 and id_usuario =".$idUsu." group by parametrizaciones.nombre,apuestas.deporte,parametrizaciones.valor,parametrizaciones.lista ";
        $tabla_deportes=DB::select($query);

        $query = "SELECT * FROM `parametrizaciones` WHERE `lista` = 'deporteApuestas' order by valor";
        $deportes= DB::select($query);

        $query = "SELECT id,usuario,dineroEnApuestas,dineroStack,cast(created_At as date) as created_at FROM `historico_apuestas_diario` order by created_at";
        $historico= DB::select($query);
 
        $mensaje=$this->obtener_mensaje( $idUsu);
        return  view('apuestas.historico')->with('apuestas',$cerradas)->with('historico',$historico)->with('tabla_deportes',$tabla_deportes)->with('deportes',$deportes)->with('mensaje',$mensaje);
    }
    public function historificacion()
    {    
        $query = "SELECT * FROM historico_apuestas ";
        $historico=DB::select($query);
        
        foreach ($historico as $a){
            $query = "INSERT INTO historico_apuestas_diario (USUARIO,DINEROENAPUESTAS,DINEROSTACK,CREATED_AT,UPDATED_AT) VALUES('".$a->usuario."','".$a->dineroEnApuestas."','".$a->dineroStack."',current_date(),current_date()) ";
            $cerradas=DB::select($query);
        }
     }
    
}
