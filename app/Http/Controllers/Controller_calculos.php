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
 class Controller_calculos extends Controller{
    
    public function rutas(){   
        $idUsu =auth()->id();
        $query = "SELECT a.*, tiempo_fiestas.tiempo_pequena, tiempo_fiestas.tiempo_grande , p.madera, p.barro, p.hierro, ( p.cereal -c.consumo_total )as cereal, e.ayuntamiento FROM aldea a, tiempo_fiestas, aldea_edificios e, aldea_producion p,consumo_aldeas c WHERE c.id_aldea = a.id and   p.id_aldea = a.id and a.id = e.id_aldea and  e.ayuntamiento = tiempo_fiestas.nivel_ayuntamiento AND  a.id_usuario = ".$idUsu;
        $aldeas=DB::select($query);
        $info_aldea = [
            "foo" => "bar",
            "bar" => "foo",
        ];
        $info_aldeas  = [];
        foreach($aldeas as $aldea)
        {
            $madeera_produ = $aldea->madera;
            $barro_produ = $aldea->barro;
            $hierro_produ = $aldea->hierro;
            $cereal_produ = $aldea->cereal;
            $sql = "SELECT mat_madera, mat_barro, mat_hierro, mat_cereal FROM `encole` where id_aldea =".$aldea->id;
            $encole = DB::select($sql);
            $madera_encole = 0;  $madera_fiesta= 0;
            $barro_encole = 0;  $barro_fiesta= 0;
            $hierro_encole = 0;$hierro_fiesta= 0;
            $cereal_encole = 0;$cereal_fiesta= 0;
            $madera_rutas = 0;
            $barro_rutas = 0;
            $hierro_rutas = 0;
            $cereal_rutas = 0;
            foreach($encole as $e)
            {
                $madera_encole = $madera_encole + $e->mat_madera;
                $barro_encole = $barro_encole + $e->mat_barro;
                $hierro_encole = $hierro_encole + $e->mat_hierro;
                $cereal_encole = $cereal_encole + $e->mat_cereal;
            }

            if($aldea->ayuntamiento >0){
                if($aldea->fiesta_pequena ==1){
                    $sql = "SELECT madera,barro,hierro,cereal FROM construcciones where nombre_ed = 'Fiesta pequeña';";
                    $fiesta = DB::select($sql);
                    foreach($fiesta as $e)
                    {
                        $madera_fiesta =  round(((1/$aldea->tiempo_pequena)*$e->madera)/24);
                        $barro_fiesta = round(((1/$aldea->tiempo_pequena)*$e->barro)/24);
                        $hierro_fiesta = round(((1/$aldea->tiempo_pequena)*$e->hierro)/24);
                        $cereal_fiesta =  round(((1/$aldea->tiempo_pequena)*$e->cereal)/24);
                    }
                }elseif(($aldea->fiesta_grande ==1)){
                    $sql = "SELECT madera,barro,hierro,cereal FROM construcciones where nombre_ed = 'Fiesta grande';";
                    $fiesta = DB::select($sql);
                    foreach($fiesta as $e)
                    {
                        $madera_fiesta =  round(((1/$aldea->tiempo_grande)*$e->madera)/24);
                        $barro_fiesta = round(((1/$aldea->tiempo_grande)*$e->barro)/24);
                        $hierro_fiesta = round(((1/$aldea->tiempo_grande)*$e->hierro)/24);
                        $cereal_fiesta =  round(((1/$aldea->tiempo_grande)*$e->cereal)/24);
                    }
                }
            }
            
            
            $sql = "SELECT * FROM rutas_comercio where (id_aldea_envia = ".$aldea->id." or id_aldea_recibe = ".$aldea->id.");";
                    $fiesta = DB::select($sql);
                    foreach($fiesta as $e)
                    {
                        if($e->id_aldea_envia == $aldea->id){
                            $madera_rutas = ($e->madera*-1) + $madera_rutas;
                            $barro_rutas = ($e->barro*-1) + $barro_rutas;
                            $hierro_rutas = ($e->hierro*-1) + $hierro_rutas;
                            $cereal_rutas = ($e->cereal*-1) + $cereal_rutas;
                        }else{
                            $madera_rutas = $e->madera + $madera_rutas;
                            $barro_rutas = $e->barro + $barro_rutas;
                            $hierro_rutas = $e->hierro + $hierro_rutas;
                            $cereal_rutas = $e->cereal + $cereal_rutas;
                        }
                    }
            $a = [
                "id_aldea" => $aldea->id,
                "nombre_aldea" => $aldea->nombre."   (".$aldea->coord_x."/".$aldea->coord_y.")",
                "madera" =>  (int)$madeera_produ - (int)$madera_encole -  (int)$madera_fiesta + (int)$madera_rutas,
                "barro" => (int)$barro_produ -(int)$barro_encole - (int)$barro_fiesta +$barro_rutas,
                "hierro" => (int)$hierro_produ - (int)$hierro_encole - (int)$hierro_fiesta + $hierro_rutas,
                "cereal" => (int)$cereal_produ -(int)$cereal_encole - (int)$cereal_fiesta +$cereal_rutas,
            ];
            array_push($info_aldeas , $a);
        }
        //print_r($info_aldeas);
        $sql ="select a1.nombre as nombreenvia, a2.nombre as nombre_recibe, rutas_comercio.* from rutas_comercio , aldea a1, aldea a2 where (id_aldea_envia in (SELECT aldea.id FROM users, aldea where users.id = aldea.id_usuario and id_usuario =  ".$idUsu.") or id_aldea_recibe in (SELECT aldea.id FROM users, aldea where users.id = aldea.id_usuario and id_usuario =".$idUsu.")) and a1.id = rutas_comercio.id_aldea_envia and a2.id = rutas_comercio.id_aldea_recibe";
        $rutas = DB::select($sql);
        $info_aldea=$info_aldeas; 
        $sql ="SELECT * FROM aldea a WHERE a.id_usuario = ".$idUsu;
        $aldeas = DB::select($sql);

        $mensaje=$this->obtener_mensaje( $idUsu);
        return view('calculos.rutasComercio')->with('mensaje',$mensaje)->with('info_aldea',$info_aldea)->with('rutas_comcercio',$rutas)->with('aldeas',$aldeas);
    }
    
    public function nuevaruta(request $info){
        $idUsu =auth()->id();
        //registro en aldeas
        
        $sql = "insert into rutas_comercio(id_aldea_envia, id_aldea_recibe,madera,barro,hierro,cereal) values(".$info->aldea_envia.",".$info->aldea_recibe.",".$info->madera.",".$info->barro.",".$info->hierro.",".$info->cereal.");";
        $encole = DB::select($sql);

        $aux=$this->creacion_mensaje('success', "Ruta generada de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_calculos@rutas');
    }
    public function editarruta(request $info)
    {    
        $idUsu =auth()->id();
        $sql = "UPDATE rutas_comercio SET madera = ".$info->madera.", barro = ".$info->barro.", hierro = ".$info->hierro.", cereal = ".$info->cereal." WHERE id = ". $info->id.";";
        $encole = DB::select($sql);

        $aux=$this->creacion_mensaje('success', "Ruta generada de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_calculos@rutas');
        
    }
    public function borrarruta(request $info)
    {    
        $idUsu =auth()->id();
        $sql = "DELETE FROM rutas_comercio WHERE id = ". $info->id.";";
        $encole = DB::select($sql);

        $aux=$this->creacion_mensaje('success', "Ruta generada de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_calculos@rutas');
    }
        
   
}
