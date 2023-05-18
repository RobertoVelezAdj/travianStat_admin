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

        $query = "SELECT  * FROM apuestas where resultado= 0  and ID_USUARIO =".$idUsu;
        $apuestas= DB::select($query);

      

        $mensaje=$this->obtener_mensaje( $idUsu);

        
        return view('apuestas.abiertas')->with('mensaje',$mensaje)->with('deportes',$deportes)->with('apuestas',$apuestas);
    }
    
    public function Crear(request $info){
    
        $idUsu =auth()->id();
    
        //SE CALCULA STAKE DEPENDIENDO PORCENTAJE 
        $query = "SELECT dineroEnApuestas, dineroStack FROM historico_apuestas WHERE usuario =".$idUsu;
        $sa=DB::select($query);
        foreach ($sa as $a){
            $total =  $a->dineroEnApuestas;
            $Pdte = $a->dineroStack;
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
        $query = "INSERT INTO apuestas(ID_USUARIO,PORCENTAJE,dineroApostado,DESCRIPCION,created_at,DEPORTE,resultadoDinero,STACK,PROBABILIDAD,resultado) VALUES(".$idUsu.",'".$paux."','".$apuesta."','".$info->descripcion."',current_date(),'".$info->deporte."','".$apuesta."',".$stack.",".$porcentaje.",'0')";
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
        $query = "SELECT dineroEnApuestas FROM historico_apuestas WHERE usuario =".$idUsu;
        $sa=DB::select($query);
        foreach ($sa as $a){
            $total =  $a->dineroEnApuestas;
        }

        $query = "SELECT dineroApostado*-1 as resultado FROM apuestas WHERE id = ".$info->idapuesta;
            $sa=DB::select($query);
            foreach ($sa as $a){
                $resultado_din =  $a->resultado;
            }             

        if($info->resultado<3){
            //Ganada o cierre
            $total= $total+round($info->cierre,2);
            $resultado_din = round($info->cierre,2)+$resultado_din;
        }
        
        $query = "UPDATE apuestas SET resultado = ".$info->resultado.", resultadodinero= ".$resultado_din." WHERE id = ".$info->idapuesta;
        $sa=DB::select($query);
        $query = "UPDATE historico_apuestas SET dineroEnApuestas = ".$total." WHERE USUARIO = ".$idUsu;
        $sa=DB::select($query);

        return redirect()->action('App\Http\Controllers\Controller_admin_apuestas@Abiertas');

    }
    public function cerradas(request $info)
    {
        $idUsu =auth()->id();

        $query = "SELECT * FROM parametrizaciones WHERE lista = 'ListaDeportesApuestas'  and nombre not in ('TITULO') order by valor";
        $deportes= DB::select($query);

        $query = "SELECT  * FROM apuestas where resultado> 0  and ID_USUARIO =".$idUsu;
        $apuestas= DB::select($query);

      

        $mensaje=$this->obtener_mensaje( $idUsu);

        
        return view('apuestas.cerradas')->with('mensaje',$mensaje)->with('deportes',$deportes)->with('apuestas',$apuestas);    
        
    }
    public function crearRegistro(request $info)
    {
        
    }
    public function EliminarRegistro(request $info)
    {
       
    }
}
