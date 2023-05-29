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
 class Controller_aldeas extends Controller{
    
    public function index(){   
         $idUsu =auth()->id();
        //se elee información de las aldeas 
        $query = "SELECT * FROM parametrizaciones WHERE lista = 'TiposAldea'  and nombre not in ('TITULO') order by valor";
        $tipos= DB::select($query);
        $query = "SELECT tiempo_fiestas.tiempo_pequena, tiempo_fiestas.tiempo_grande,a.id as id_aldea, a.coord_x, a.coord_y,a.nombre,a.tipo, p.nombre as tipo_aldea,a.fiesta_pequena, a.fiesta_grande, ap.madera, ap.barro, ap.hierro,( ap.cereal -c.consumo_total )as cereal,ap.puntos_cultura , e.ayuntamiento FROM aldea a,aldea_producion ap, aldea_edificios e, parametrizaciones p,tiempo_fiestas,consumo_aldeas c WHERE c.id_aldea = a.id and e.ayuntamiento = tiempo_fiestas.nivel_ayuntamiento and p.lista = 'TiposAldea'  and p.nombre not in ('TITULO') and p.valor = a.tipo and  e.id_aldea = a.id and ap.id_aldea = a.id and  a.id_usuario = ".$idUsu;
        $aldeas= DB::select($query);
  
        $query = "select sum(puntos_cultura) as pc_aldeas from aldea, aldea_producion p where p.id_aldea = aldea.id and  aldea.id_usuario =".$idUsu;
        $pc_totales_aldeas=DB::select($query);
        foreach($pc_totales_aldeas as $s)
        {
            $pc_aldeas = $s->pc_aldeas;
        }
        $fiesta_grande = 2000;
        $fiesta_pequeña = 500;
        $query = "SELECT pc_max_fiesta_pequeña, pc_max_fiesta_grande, velocidad_fiesta  FROM users, servidor, velocidad_servidores WHERE users.servidor = servidor.id and servidor.velocidad = velocidad_servidores.id and users.id = ".$idUsu;
        $resultado=DB::select($query);
        foreach ($resultado as $a){
            $fiesta_pequeña =  $a->pc_max_fiesta_pequeña;
            $fiesta_grande =  $a->pc_max_fiesta_grande;
            $velocidad_fiesta =  $a->velocidad_fiesta;
        }

        if ($pc_aldeas > $fiesta_grande){
            $pc_fiesta_grande = $fiesta_grande;
        }else{
            $pc_fiesta_grande = $pc_aldeas;
        }
        //Se calcula el bono de pc de alianza
        $filosofia = 1;
        /*$query = "SELECT alianzas.filosofia FROM users, alianzas where alianzas.id = users.alianza and users.id = ".$idUsu;
        $filo=DB::select($query);
        foreach($filo as $s)
        {
            $filosofia = $s->filosofia;
        } */
        $mensaje=$this->obtener_mensaje( $idUsu);

        
        return view('aldea.index')->with('mensaje',$mensaje)->with('tipos',$tipos)->with('aldeas',$aldeas)->with('putnos_fiesta_grande',$pc_fiesta_grande)->with('putnos_fiesta_pequeña',$fiesta_pequeña)->with('velocidad_fiesta',$velocidad_fiesta)->with('filosofia',$filosofia);
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
        $query = "INSERT INTO aldea_tropas(id_aldea)VALUES(".$id_aldea.")";
        $tipos= DB::select($query);
        $query = "INSERT INTO aldea_producion(id_aldea)VALUES(".$id_aldea.")";
        $tipos= DB::select($query);

        $aux=$this->creacion_mensaje('success', "Aldea generada de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@index');
    }
    public function editar(request $info) {
        $idUsu =auth()->id();
        $query = "UPDATE aldea SET tipo = '".$info->tipo."', nombre = '".$info->nombreAldea."', fiesta_grande = ".$info->fiesta_grande.",fiesta_pequena = ".$info->fiesta_pequena." WHERE id = ".$info->idAldea.";";
        $aldea=DB::select($query);
  
        $query = "UPDATE aldea_producion SET  puntos_cultura = ".$info->puntos_cultura.",madera = ".$info->madera.", barro = ".$info->barro.", hierro = ".$info->hierro.", cereal = ".$info->cereal." WHERE id_aldea = ".$info->idAldea.";";
        $aldea=DB::select($query);

        $aux=$this->creacion_mensaje('success', "Aldea editada de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@index');

        }
    public function borrar(request $info){
        $idUsu =auth()->id();

        $query = "DELETE FROM aldea WHERE id =".$info->idAldea." ;";
        $aldea=DB::select($query);
        $query = "DELETE FROM aldea_producion WHERE id_aldea =".$info->idAldea." ;";
        $aldea=DB::select($query);
        $query = "DELETE FROM aldea_edificios WHERE id_aldea =".$info->idAldea." ;";
        $aldea=DB::select($query);

        $aux=$this->creacion_mensaje('success', "Aldea borrada de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@index');
        
        }
    public function edificios(){   
        $idUsu =auth()->id();
  
        $query = "SELECT a.nombre, a.coord_x, a.coord_y, e.cuartel, e.cuartel_g, e.establo, e.establo_g, e.taller, e.ayuntamiento, e.p_torneos, e.o_comercio , e.id_aldea FROM aldea_edificios e, aldea a WHERE  a.id = e.id_aldea and a.id_usuario = ".$idUsu;
        $aldeas=DB::select($query);
           
        $mensaje=$this->obtener_mensaje( $idUsu);
   
           
           return view('aldea.edificios')->with('mensaje',$mensaje)->with('aldeas',$aldeas);
       }
    public function editarEdificios(request $info){   
        $idUsu =auth()->id();

        $query = "UPDATE aldea_edificios SET cuartel='".$info->cuartel."',cuartel_g='".$info->cuartel_g."',establo='".$info->establo."',establo_g='".$info->establo_g."',taller='".$info->taller."',ayuntamiento='".$info->ayuntamiento."',p_torneos='".$info->p_torneos."',o_comercio='".$info->o_comercio."' WHERE id_aldea = ".$info->id_aldea;
        $aldeas=DB::select($query);
 
       $aux=$this->creacion_mensaje('success', "Aldea editada de forma correcta.",$idUsu);

       return redirect()->action('App\Http\Controllers\Controller_aldeas@edificios');

        }
    public function mistropas(){   
    $idUsu =auth()->id();
    //select de tropas
    $query = "select a.nombre,coord_x, coord_y, t.tropa_1, t.tropa_2, t.tropa_3, t.tropa_4, t.tropa_5, t.tropa_6, t.tropa_7, t.tropa_8, t.tropa_9, t.tropa_10, t.tropa_11 from aldea a, aldea_tropas t where a.id = t.id_aldea and a.id_usuario = ".$idUsu;
    $tropas= DB::select($query);
    
    $query = "SELECT t.nombre_tropa FROM tropas t, users u WHERE   t.raza = u.raza and u.id = ".$idUsu;
    $tipo_tropas= DB::select($query);
    
    $mensaje=$this->obtener_mensaje( $idUsu);
    return view('aldea.mistropas')->with('mensaje',$mensaje)->with('tropas',$tropas)->with('tipo_tropas',$tipo_tropas);
    }
    public function actualizar(request $info){
        $idUsu =auth()->id();
        $vowels = array("select", "query", "insert", "update");
        $cadena_limpia = str_replace($vowels, "", $info->madera);
        $cadena = explode(" ", $cadena_limpia);
        $id_aldea = 0;
        $nombre = 0;
        $tropa_1 = 1;
        $tropa_2 = 2;
        $tropa_3 = 3;
        $tropa_4 = 4;
        $tropa_5 = 5;
        $tropa_6 = 6;
        $tropa_7 = 7;
        $tropa_8 = 8;
        $tropa_9 = 9;
        $tropa_10 = 10;
        $tropa_11 = 11;
        for($i = 0; $i < sizeof($cadena);$i=$i+12)
        {
            $id_aldea=0;
            $query = "SELECT id FROM aldea a WHERE   a.nombre = '".$cadena[$nombre]."'";
            $resultado= DB::select($query);
            foreach ($resultado as $a){
                $id_aldea =  $a->id;
            }

            $query = "UPDATE aldea_tropas SET tropa_1='".$cadena[$tropa_1]."',tropa_2='".$cadena[$tropa_2]."',tropa_3='".$cadena[$tropa_3]."',tropa_4='".$cadena[$tropa_4]."',tropa_5='".$cadena[$tropa_5]."',tropa_6='".$cadena[$tropa_6]."',tropa_7='".$cadena[$tropa_7]."',tropa_8='".$cadena[$tropa_8]."',tropa_9='".$cadena[$tropa_9]."',tropa_10='".$cadena[$tropa_10]."',tropa_11='".$cadena[$tropa_11]."' WHERE  ID_ALDEA= ".$id_aldea;
            $tipo_tropas= DB::select($query);

            $nombre=$nombre+12;
            $tropa_1 = $tropa_1+12;
            $tropa_2 = $tropa_2+12;
            $tropa_3 = $tropa_3+12;
            $tropa_4 = $tropa_4+12;
            $tropa_5 = $tropa_5+12;
            $tropa_6 = $tropa_6+12;
            $tropa_7 = $tropa_7+12;
            $tropa_8 = $tropa_8+12;
            $tropa_9 = $tropa_9+12;
            $tropa_10 =$tropa_10+12;
            $tropa_11 =$tropa_11 +12;
        }

        $aux=$this->creacion_mensaje('success', "Tropas de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@mistropas');
        }
    public function actualizarprod(request $info){
        $idUsu =auth()->id();
        $vowels = array("select", "query", "insert", "update","‭");
        $cadena_limpia = str_replace($vowels, "", $info->madera);
        $cadena = explode(" ", $cadena_limpia);
        $id_aldea = 0;
        $nombre = 0;
        $madera = 1;
        $barro = 2;
        $hierro = 3;
        $cereal = 4;
        $punto = ".";
        for($i = 0; $i < sizeof($cadena);$i=$i+5)
        {
            $id_aldea=0;
            $query = "SELECT id FROM aldea a WHERE   a.nombre = '".$cadena[$nombre]."'";
            $resultado= DB::select($query);
            foreach ($resultado as $a){
                $id_aldea =  $a->id;
            }
    
            $query = "UPDATE `aldea_producion` SET `madera`='".trim(str_replace($punto, "", $cadena[$madera]))."',`barro`='".trim(str_replace($punto, "", $cadena[$barro]))."',`hierro`='".trim(str_replace($punto, "", $cadena[$hierro]))."',`cereal`='".trim(str_replace($punto, "", $cadena[$cereal]))."' WHERE  ID_ALDEA= ".$id_aldea;
            //echo $query;
            $tipo_tropas= DB::select($query);

            $nombre=$nombre+5;
            $madera = $madera+5;
            $barro = $barro+5;
            $hierro = $hierro+5;
            $cereal = $cereal+5;
        }

        $aux=$this->creacion_mensaje('success', "Tropas de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@index');
        }   
    public function actualizarpc(request $info){
        $idUsu =auth()->id();
        $vowels = array("select", "query", "insert", "update");
        $cadena_limpia = str_replace($vowels, "", $info->madera);
        $cadena = explode(" ", $cadena_limpia);
        $id_aldea = 0;
        $nombre = 0;
        $pc_dia = 1;
        $fiestas = 2;
        $tropas = 3;
        $slot = 4;
        for($i = 0; $i < sizeof($cadena);$i=$i+5)
        {
            $id_aldea=0;
            $query = "SELECT id FROM aldea a WHERE   a.nombre = '".$cadena[$nombre]."'";
            $resultado= DB::select($query);
            foreach ($resultado as $a){
                $id_aldea =  $a->id;
            }

            $query = "UPDATE aldea_producion SET puntos_cultura='".$cadena[$pc_dia]."' WHERE  ID_ALDEA= ".$id_aldea;
            $tipo_tropas= DB::select($query);

            $nombre=$nombre+5;
            $pc_dia = $pc_dia+5;
            $fiestas = $fiestas+5;
            $tropas = $tropas+5;
            $slot = $slot+5;
        }

        $aux=$this->creacion_mensaje('success', "Tropas de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@index');
        }
    

}
