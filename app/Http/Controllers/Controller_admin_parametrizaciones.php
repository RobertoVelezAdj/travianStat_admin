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
class Controller_admin_parametrizaciones extends Controller
{
   
 
    public function index(){
        $idUsu =auth()->id();
        $query = "SELECT  distinct lista, descripcion FROM parametrizaciones ";
        
        $parametrizaciones=DB::select($query);
        $mensaje=$this->obtener_mensaje( $idUsu);

        return view('parametrizaciones.index')->with('parametrizaciones',$parametrizaciones)->with('mensaje',$mensaje);
    }
    
    public function crearLista(request $info) {
        $idUsu =auth()->id();
        $query = "INSERT INTO parametrizaciones( nombre, valor, lista, descripcion) VALUES ('TITULO','TITULO','".$info->nombreParam."','".$info->descripcion."')";
        $parametrizaciones=DB::select($query);
        
        $aux=$this->creacion_mensaje('success', "Lista creada correctamente",$idUsu);

        return redirect()->action('App\Http\Controllers\Controller_admin_parametrizaciones@index');

    }
    public function EliminarLista(request $info) { 
        $idUsu =auth()->id();
        $query = "DELETE FROM parametrizaciones WHERE lista = '".$info->parametrizacion."'";
        $tropas=DB::select($query);
        $mensaje ="Toast.fire({
            icon: 'success',
            title: 'Eliminado correctamente'
          });";
        
          $aux=$this->creacion_mensaje('success', "Lista eliminada correctamente",$idUsu);
        
        return redirect()->action('App\Http\Controllers\Controller_admin_parametrizaciones@index');    
    }
    public function MostrarLista(request $info)
    {
        $idUsu =auth()->id();
        if($info->parametrizacion==''){
            $info->parametrizacion = $this->obtener_lista( $idUsu);
        }
         
        $query = "SELECT  id, nombre,valor,lista FROM parametrizaciones where lista = '".$info->parametrizacion."'  and nombre <>'TITULO'";
        $parametrizaciones=DB::select($query);
         
        $query = "SELECT  distinct descripcion FROM parametrizaciones where lista = '.$info->parametrizacion.' ";
        $sa=DB::select($query);
        $descripcion= "";
        foreach ($sa as $a){
            $descripcion =  $a->descripcion; 
        }
        $mensaje=$this->obtener_mensaje( $idUsu);

        return view('parametrizaciones.modificar')->with('parametrizaciones',$parametrizaciones)->with('nombre',$info->parametrizacion)->with('descripcion',$descripcion)->with('mensaje',$mensaje);     
    }
    public function crearRegistro(request $info)
    {
        $idUsu =auth()->id();
        $query = "SELECT distinct descripcion FROM parametrizaciones where lista = '".$info->parametrizacion."' ";
        $sa=DB::select($query);
        $descripcion= "";
        
        foreach ($sa as $a){
            $descripcion =  $a->descripcion; 
        }
        $query = "INSERT INTO parametrizaciones( nombre, valor, lista, descripcion) VALUES ('".$info->nombre."','".$info->valor."','".$info->parametrizacion."','".$descripcion."')";
        $sa=DB::select($query);
        
        $aux=$this->creacion_mensaje('success', "Registro creado correctamente",$idUsu);
        $aux=$this->paso_mensaje($info->parametrizacion, '',$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_admin_parametrizaciones@MostrarLista');    
 
    }
    public function EliminarRegistro(request $info)
    {
        $idUsu =auth()->id();
        $query = "DELETE FROM `parametrizaciones` WHERE id = '".$info->id."' ";
        $sa=DB::select($query);
        echo $query;
        $query = "SELECT  id, nombre,valor,lista FROM parametrizaciones where lista = '".$info->parametrizacion."'  and nombre <>'TITULO'";
        $parametrizaciones=DB::select($query);
         
        $query = "SELECT  distinct descripcion FROM parametrizaciones where lista = '.$info->parametrizacion.' ";
        $sa=DB::select($query);
        $descripcion= "";
        foreach ($sa as $a){
            $descripcion =  $a->descripcion; 
        }
        $aux=$this->creacion_mensaje('success', "Registro eliminado correctamente",$idUsu);
        $aux=$this->paso_mensaje($info->parametrizacion, '',$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_admin_parametrizaciones@MostrarLista');    
    }
}
