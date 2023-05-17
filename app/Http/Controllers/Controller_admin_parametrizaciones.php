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
    private function finIndex($mensaje){

        $query = "SELECT  distinct lista, descripcion FROM parametrizaciones ";
        
        $parametrizaciones=DB::select($query);
           //sucess error, warning info 
         return view('parametrizaciones.index')->with('parametrizaciones',$parametrizaciones)->with('mensaje',$mensaje);
    }
    private function finModificar($info,$mensaje){

        $query = "SELECT  id, nombre,valor,lista FROM parametrizaciones where lista = '".$info->parametrizacion."'  and nombre <>'TITULO'";
        $parametrizaciones=DB::select($query);
         
        $query = "SELECT  distinct descripcion FROM parametrizaciones where lista = '.$info->parametrizacion.' ";
        $sa=DB::select($query);
        $descripcion= "";
        foreach ($sa as $a){
            $descripcion =  $a->descripcion; 
        }
        return view('parametrizaciones.modificar')->with('parametrizaciones',$parametrizaciones)->with('nombre',$info->parametrizacion)->with('descripcion',$descripcion)->with('mensaje',$mensaje);     
    }
    public function index()
    {
        return $this->finIndex("");
    }
    
    public function crearLista(request $info)
    {
        $query = "INSERT INTO parametrizaciones( nombre, valor, lista, descripcion) VALUES ('TITULO','TITULO','".$info->nombreParam."','".$info->descripcion."')";
        $parametrizaciones=DB::select($query);
        $mensaje ="Toast.fire({
            icon: 'success',
            title: 'Insertado correctamente'
          });";
          return $this->finIndex($mensaje);
    }
    public function EliminarLista(request $info)
    {
        
        $query = "DELETE FROM parametrizaciones WHERE lista = '".$info->parametrizacion."'";
        $tropas=DB::select($query);
        $mensaje ="Toast.fire({
            icon: 'success',
            title: 'Eliminado correctamente'
          });";
        return $this->finIndex($mensaje);
    }
    public function MostrarLista(request $info)
    {
        $mensaje="";
        return $this->finModificar($info, $mensaje);
        ;     
        
    }
    public function crearRegistro(request $info)
    {
        $query = "SELECT distinct descripcion FROM parametrizaciones where lista = '".$info->parametrizacion."' ";
        $sa=DB::select($query);
        $descripcion= "";
        
        foreach ($sa as $a){
            $descripcion =  $a->descripcion; 
        }
        $query = "INSERT INTO parametrizaciones( nombre, valor, lista, descripcion) VALUES ('".$info->nombre."','".$info->valor."','".$info->parametrizacion."','".$descripcion."')";
        $sa=DB::select($query);
        $mensaje ="Toast.fire({
            icon: 'success',
            title: 'Insertado correctamente'
          });";
        return $this->finModificar($info, $mensaje);
        ;  
    }
    public function EliminarRegistro(request $info)
    {
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
        $mensaje ="Toast.fire({
            icon: 'success',
            title: 'Eliminado correctamente'
          });";
        return $this->finModificar($info, $mensaje);       
    }
}
