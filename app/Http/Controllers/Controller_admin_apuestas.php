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
 class Controller_admin_apuestas extends Controller
{
    
    public function Abiertas(){   
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
    
    public function Crear(request $info){
    
        $idUsu =auth()->id();
    
        //SE CALCULA STAKE DEPENDIENDO PORCENTAJE 
        $query = "SELECT dineroEnApuestas, dineroStack FROM historico_apuestas WHERE usuario =".$idUsu;
        $sa=DB::select($query);
        foreach ($sa as $a){
            $Pdte =  $a->dineroEnApuestas;
            $total = $a->dineroStack;
        }
        $paux =str_replace(",",".",$info->porcentaje);
        $porcentaje = round(100/str_replace(",",".",$info->porcentaje),2);

        if($porcentaje<25){
            $apuesta = round($total*0.01,2);
            $stack = 1;
        }elseif($porcentaje<45){
            $apuesta = round($total*0.02,2);
            $stack = 2;
        }elseif($porcentaje<65){
            $apuesta = round($total*0.03,2);
            $stack = 3;
        }elseif($porcentaje<85){
            $apuesta = round($total*0.04,2);
            $stack = 4;
        }else{
            $apuesta = round($total*0.05,2);
            $stack = 5;
        }
        //$apuesta = round((0.01*$info->stack)*$total,2);
        $total= $total-$apuesta;
        $Pdte = $Pdte+$apuesta;
      
        //insert
        $query = "INSERT INTO apuestas(ID_USUARIO,PORCENTAJE,dineroApostado,DESCRIPCION,cast(apuestas.created_at as date) as created_at,DEPORTE,resultadoDinero,STACK,PROBABILIDAD,resultado) VALUES(".$idUsu.",'".$paux."','".$apuesta."','".$info->descripcion."',current_date(),'".$info->deporte."','".$apuesta."',".$stack.",".$porcentaje.",'0')";
        $sa=DB::select($query);
        //resto dinero
        $query = "UPDATE historico_apuestas SET dineroStack = ".$total.",  dineroEnApuestas = ".$Pdte." WHERE USUARIO = ".$idUsu;
        $sa=DB::select($query);
    
        $aux=$this->creacion_mensaje('success', "Apuesta creada correctamente",$idUsu);
        
        return redirect()->action('App\Http\Controllers\Controller_admin_apuestas@Abiertas');

        }
    public function FinalizarApuesta(request $info)
    {
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
    public function cerradas(request $info)
    {
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
    
        $query = "select created_at as fecha, sum(resultadodinero) as resultadodinero , (select count(*)from apuestas a where resultado > 0 and apuestas.created_at = a.created_at and id_usuario =".$idUsu.") as contador from apuestas where resultado> 0 and id_usuario =".$idUsu." group by fecha order by fecha desc";
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
    public function historificacion(request $info)
    {    
        $query = "SELECT * FROM historico_apuestas ";
        $historico=DB::select($query);
        
        foreach ($historico as $a){
            $query = "INSERT INTO historico_apuestas_diario (USUARIO,DINEROENAPUESTAS,DINEROSTACK,CREATED_AT,UPDATED_AT) VALUES('".$a->usuario."','".$a->dineroEnApuestas."','".$a->dineroStack."',current_date(),current_date()) ";
            $cerradas=DB::select($query);
        }
     }
    
}
