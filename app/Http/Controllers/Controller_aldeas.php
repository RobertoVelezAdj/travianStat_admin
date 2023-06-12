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

        $query = "SELECT * FROM parametrizaciones WHERE lista = 'TiposTareas'  and nombre not in ('TITULO') order by valor";
        $tareas= DB::select($query);

        $query = "SELECT tiempo_fiestas.tiempo_pequena, tiempo_fiestas.tiempo_grande,a.id as id_aldea, a.coord_x, a.coord_y,a.nombre,a.tipo, p.nombre as tipo_aldea,a.fiesta_pequena, a.fiesta_grande, ap.madera, ap.barro, ap.hierro,( ap.cereal -c.consumo_total )as cereal,ap.cereal as cereal_sintropas,ap.puntos_cultura , e.ayuntamiento FROM aldea a,aldea_producion ap, aldea_edificios e, parametrizaciones p,tiempo_fiestas,consumo_aldeas c WHERE c.id_aldea = a.id and e.ayuntamiento = tiempo_fiestas.nivel_ayuntamiento and p.lista = 'TiposAldea'  and p.nombre not in ('TITULO') and p.valor = a.tipo and  e.id_aldea = a.id and ap.id_aldea = a.id and  a.id_usuario = ".$idUsu;
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
       
        
        return view('aldea.index')->with('mensaje',$mensaje)->with('tareas',$tareas)->with('tipos',$tipos)->with('aldeas',$aldeas)->with('putnos_fiesta_grande',$pc_fiesta_grande)->with('putnos_fiesta_pequeña',$fiesta_pequeña)->with('velocidad_fiesta',$velocidad_fiesta)->with('filosofia',$filosofia);
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

       
        if( $info->check=="on"){
            $query = "SELECT nombre,valor FROM parametrizaciones WHERE lista = '".$info->tipotarea."'  and nombre not in ('TITULO') order by valor";
            $resultado= DB::select($query);

            foreach ($resultado as $a){
                $query = "INSERT INTO tareas(id_aldea,estado,titulo,descripcion,prioridad)VALUES(".$id_aldea.",0,'".$a->nombre."','',".$a->valor.")";
                $tipos= DB::select($query);
            }
        }
        $aux=$this->creacion_mensaje('success', "Aldea generada de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@index');
    }
    public function redirec(){
        
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
        $query = "SELECT e.id , e.id_tropa, e.id_aldea FROM aldea_encole e, aldea a  where a.id = e.id_aldea and a.id_usuario = ".$idUsu;
        $resultado=DB::select($query);
        foreach ($resultado as $a){

            $cantidades = $this->cantidad($a->id_tropa, $a->id_aldea);
            //borro el registro anterior
            $query = "DELETE FROM aldea_encole WHERE id =  ".$a->id;
            $t=DB::select($query);
        }
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
        $vowels = array("select", "query", "insert", "update","‭","‬","\t");
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

        $t1 = 0;
        $t2 = 0;
        $t3 = 0;
        $t4 = 0;
        $t5 = 0;
        $t6 = 0;
        $t7 = 0;
        $t8 = 0;
        $t9 = 0;
        $t10  = 0;
        $t11 = 0;
        $nombrealdea ='';
        $contador =-1;
        print_r($cadena);
        for($i = 0; $i <sizeof($cadena);$i=$i+1)
        {
            if(strlen($cadena[$i])>0){
                $contador =(int)$contador+1;
            }

            if($contador==$nombre&& strlen($cadena[$i])>0){
                $nombrealdea =$cadena[$i];
            }else if($contador==$tropa_1&& strlen($cadena[$i])>0){
                $t1 = $cadena[$i];
            }
            else if($contador==$tropa_2&& strlen($cadena[$i])>0){
                $t2 = $cadena[$i];
            }
            else if($contador==$tropa_3&& strlen($cadena[$i])>0){
                $t3 = $cadena[$i];
            }
            else if($contador==$tropa_4&& strlen($cadena[$i])>0){
                $t4 = $cadena[$i];
            }
            else if($contador==$tropa_5&& strlen($cadena[$i])>0){
                $t5 = $cadena[$i];
            }
            else if($contador==$tropa_6&& strlen($cadena[$i])>0){
                $t6 = $cadena[$i];
            }
            else if($contador==$tropa_7&& strlen($cadena[$i])>0){
                $t7 = $cadena[$i];
            }
            else if($contador==$tropa_8&& strlen($cadena[$i])>0){
                $t8 = $cadena[$i];
            }
            else if($contador==$tropa_9&& strlen($cadena[$i])>0){
                $t9 = $cadena[$i];
            }
            else if($contador==$tropa_10&& strlen($cadena[$i])>0){
                $t10 = $cadena[$i];
            }else if($contador==$tropa_11&& strlen($cadena[$i])>0){

                $t11 = $cadena[$i];
                $id_aldea=0;
                $query = "SELECT id FROM aldea a WHERE   a.nombre = '".$nombrealdea."'";
                $resultado= DB::select($query);
                foreach ($resultado as $a){
                    $id_aldea =  $a->id;
                }

                $query = "UPDATE aldea_tropas SET tropa_1='".$t1."',tropa_2='".$t2."',tropa_3='".$t3."',tropa_4='".$t4."',tropa_5='".$t5."',tropa_6='".$t6."',tropa_7='".$t7."',tropa_8='".$t8."',tropa_9='".$t9."',tropa_10='".$t10."',tropa_11='".$t11."' WHERE  ID_ALDEA= ".$id_aldea;
                echo $query;
                $tipo_tropas= DB::select($query);
                $t1 = 0;
                $t2 = 0;
                $t3 = 0;
                $t4 = 0;
                $t5 = 0;
                $t6 = 0;
                $t7 = 0;
                $t8 = 0;
                $t9 = 0;
                $t10  = 0;
                $t11 = 0;
                $nombrealdea ='';
                $contador =-1;
            }

           

           /* $nombre=$nombre+12;
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
            $tropa_11 =$tropa_11 +12;*/
        }

       // $aux=$this->creacion_mensaje('success', "Tropas de forma correcta.",$idUsu);
        //return redirect()->action('App\Http\Controllers\Controller_aldeas@mistropas');
        }
    public function actualizarprod(request $info){
        $idUsu =auth()->id();
        $vowels = array("select", "query", "insert", "update","‭","‬","\t");
        $cadena_limpia = str_replace($vowels, " ", $info->madera);
        $cadena = explode(" ", $cadena_limpia);
        $id_aldea = 0;
        $nombre = 0;
        $madera = 1;
        $barro = 2;
        $hierro = 3;
        $cereal = 4;
        $punto = ".";
        //print_r($cadena);
       // echo sizeof($cadena)."TmÑO!";
        $nombrealdea ='';
        $contador =-1;
        $maderap = 0;
        $barrop = 0;
        $hierrop = 0;
        $cerealp = 0;
        $contador =-1;
        for($i = 0; $i <sizeof($cadena);$i=$i+1)
        {
            if(strlen($cadena[$i])>0){
                $contador =(int)$contador+1;
            }
            
            if($contador==$nombre&& strlen($cadena[$i])>0){
                $nombrealdea =$cadena[$i];
            }else if($contador==$madera&& strlen($cadena[$i])>0){
                $maderap =$cadena[$i];
            }else if($contador==$barro&& strlen($cadena[$i])>0){
                $barrop =$cadena[$i];
            }else if($contador==$hierro&& strlen($cadena[$i])>0){
                $hierrop =$cadena[$i];
            }else if($contador==$cereal&& strlen($cadena[$i])>0){
                $cerealp =$cadena[$i];
                
                $query = "SELECT id FROM aldea a WHERE   a.nombre = '".$nombrealdea."'";
                $resultado= DB::select($query);
                foreach ($resultado as $a){
                    $id_aldea =  $a->id;
                }
        
                $query = "UPDATE `aldea_producion` SET `madera`='".trim(str_replace($punto, "", $maderap))."',`barro`='".trim(str_replace($punto, "", $barrop))."',`hierro`='".trim(str_replace($punto, "", $hierrop))."',`cereal`='".trim(str_replace($punto, "", $cerealp))."' WHERE  ID_ALDEA= ".$id_aldea;
                //echo $query.";";
                $tipo_tropas= DB::select($query);
                $contador =-1;
                $maderap = 0;
                $barrop = 0;
                $hierrop = 0;
                $cerealp = 0;
                $id_aldea=0;
            }

            

          /*  $nombre=$nombre+5;
            $madera = $madera+5;
            $barro = $barro+5;
            $hierro = $hierro+5;
            $cereal = $cereal+5;*/
        }

        $aux=$this->creacion_mensaje('success', "Tropas de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@index');
        }   
    public function actualizarpc(request $info){
        $idUsu =auth()->id();
        $vowels = array("select", "query", "insert", "update","‭","‬","\t");
        $cadena_limpia = str_replace($vowels, "", $info->madera);
        $cadena = explode(" ", $cadena_limpia);
        $id_aldea = 0;
        $nombre = 0;
        $pc_dia = 1;
        $fiestas = 2;
        $tropas = 3;
        $slot = 4;
        $contador =-1;
        print_r($cadena);
        for($i = 0; $i < sizeof($cadena);$i=$i+1)
        {
            if(strlen($cadena[$i])>0){
                $contador =(int)$contador+1;
            }
            
            if($contador==$nombre&& strlen($cadena[$i])>0){
                $nombrealdea =$cadena[$i];
            }else if($contador==$pc_dia&& strlen($cadena[$i])>0){
               
                $id_aldea=0;
                $query = "SELECT id FROM aldea a WHERE   a.nombre = '".$nombrealdea."'";
                $resultado= DB::select($query);
                foreach ($resultado as $a){
                    $id_aldea =  $a->id;
                }
    
                $query = "UPDATE aldea_producion SET puntos_cultura='".$cadena[$i]."' WHERE  ID_ALDEA= ".$id_aldea;
                echo $query;
                $tipo_tropas= DB::select($query);
            }else if($contador ==$slot){
                $contador =-1;
            }

           
            /*$nombre=$nombre+5;
            $pc_dia = $pc_dia+5;
            $fiestas = $fiestas+5;
            $tropas = $tropas+5;
            $slot = $slot+5;*/
        }

        $aux=$this->creacion_mensaje('success', "Tropas de forma correcta.",$idUsu);
        //return redirect()->action('App\Http\Controllers\Controller_aldeas@index');
        }
    public function tareas(){
        $idUsu =auth()->id();
       
        $query = "select a.id as  id_aldea, a.nombre, a.coord_x, a.coord_y, t.prioridad, t.titulo, t.descripcion, t.id as id_tarea from  users u, aldea a, tareas t where  a.id_usuario = u.id and a.id = t.id_aldea and t.estado = 0 and u.id =".$idUsu." order by a.id, t.prioridad";
        $tareas= DB::select($query);
        $query = "select a.nombre, a.coord_x, a.coord_y, t.prioridad, t.titulo, t.descripcion from  users u, aldea a, tareas t where  a.id_usuario = u.id and a.id = t.id_aldea and t.estado = 0 and u.id =".$idUsu;
        $resultado= DB::select($query);
        foreach ($resultado as $a){
           // $script =  "  <script>$(function () {$('#example".$contador."').DataTable({'responsive': true, 'lengthChange': false, 'autoWidth': false,'buttons': ['copy', 'csv', 'excel', 'pdf", 'print']}).buttons().container().appendTo('example".$contador."_wrapper .col-md-6:eq(0)'); });</script>";
        }
     
        $query = "SELECT * FROM aldea WHERE id_usuario =".$idUsu;
        $aldeas=DB::select($query);


        $mensaje=$this->obtener_mensaje( $idUsu);
        return view('aldea.tareas')->with('mensaje',$mensaje)->with('tareas',$tareas)->with('aldeas',$aldeas);
        }
    public function nuevaTarea(request $info){
        $idUsu =auth()->id();
        
        $query = "select a.id, a.nombre, a.coord_x, a.coord_y, t.prioridad, t.titulo, t.descripcion from  users u, aldea a, tareas t where  a.id_usuario = u.id and a.id = t.id_aldea and t.estado = 0 and u.id =".$idUsu." order by a.id, t.prioridad";
        $tareas= DB::select($query);
        
        
        $query = "SELECT * FROM aldea WHERE id_usuario =".$idUsu;
        $aldeas=DB::select($query);

        //todas las tareas updatear +1 la prioridad si es inferior
        $query = "SELECT * FROM `tareas` where estado = 0 and id_aldea = ".$info->id_aldea." order by prioridad";
        $resultado= DB::select($query);
        foreach ($resultado as $a){
            if($a->prioridad >= $info->prioridad){
                $query = "UPDATE tareas t SET `prioridad`=".($a->prioridad+1).",updated_at=current_date() WHERE t.id = ".$a->id;
                $ejecucion= DB::select($query);
            }
        }
        //insertar la tarea
        $query = "INSERT INTO tareas(id_aldea,estado,titulo,descripcion,prioridad)VALUES(".$info->id_aldea.",0,' ".$info->titulo."',' ".$info->Descripcion."',".$info->prioridad.")";        
        $aldeas=DB::select($query);
        $aux=$this->creacion_mensaje('success', "Aldea generada de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@tareas');
        }
    public function completarTarea(request $info)
        {    
        $idUsu =auth()->id();        
        $query = "UPDATE `tareas` SET Estado = '1',updated_at=current_date() WHERE id = ".$info->id_tarea.";";
        $tareas=DB::select($query);

        $query = "SELECT * FROM `tareas` where estado = 0 and id_aldea = ".$info->id_aldea." order by prioridad";
        $resultado= DB::select($query);
        foreach ($resultado as $a){
            if($a->prioridad >= $info->prioridad){
                $query = "UPDATE tareas t SET `prioridad`=".($a->prioridad-1)." WHERE t.id = ".$a->id;
                $ejecucion= DB::select($query);
            }
        }
        return redirect()->action('App\Http\Controllers\Controller_aldeas@tareas');
    }
    public function editartarea(request $info)
        {    
        $idUsu =auth()->id();     
        $query = "UPDATE tareas SET prioridad = '".$info->prioridad."', titulo = ' ".$info->titulo."' , descripcion =' ".$info->descripcion."' WHERE id = ".$info->id_tarea.";";
        $tareas=DB::select($query);
        $prioridad_antigua = 0;
        $query = "SELECT * FROM `tareas` WHERE id = ".$info->id_tarea.";";
        $resultado=DB::select($query);

        foreach ($resultado as $a){
            $prioridad_antigua = $a->prioridad;
        }
        //si es la misma prioridad no hacer nada
        if($prioridad_antigua!=$info->prioridad){
            $query = "SELECT * FROM `tareas` where estado = 0 and   id <>".$info->id_tarea." and id_aldea = ".$info->id_aldea." order by prioridad";
            $resultado= DB::select($query);
            foreach ($resultado as $a){
                if($a->prioridad >= $info->prioridad){
                    $query = "UPDATE tareas t SET `prioridad`=".($a->prioridad+1)." WHERE t.id = ".$a->id;
                    $ejecucion= DB::select($query);
                }
            }
        }
       
        return redirect()->action('App\Http\Controllers\Controller_aldeas@tareas');
    }
    public function encole()
    {
        $idUsu =auth()->id();
        //$aldeas_usuario = DB::table('aldea')->where('id_cuenta',$idUsu)->get();
        $query = "SELECT a.id as id_aldea, a.coord_x, a.coord_y,a.nombre ,a.tipo, p.nombre as tipo_aldea,a.fiesta_pequena, a.fiesta_grande, ap.madera, ap.barro, ap.hierro,( ap.cereal -c.consumo_total )as cereal,ap.puntos_cultura , e.ayuntamiento FROM aldea a,aldea_producion ap, aldea_edificios e, parametrizaciones p,tiempo_fiestas,consumo_aldeas c WHERE c.id_aldea = a.id and e.ayuntamiento = tiempo_fiestas.nivel_ayuntamiento and p.lista = 'TiposAldea'  and p.nombre not in ('TITULO') and p.valor = a.tipo and  e.id_aldea = a.id and ap.id_aldea = a.id and  a.id_usuario = ".$idUsu;
        $aldeas= DB::select($query);

        $query = "SELECT t.nombre_tropa , t.id FROM tropas t, users u WHERE   t.raza = u.raza and u.id = ".$idUsu." order by t.orden";
        $tipo_tropas= DB::select($query);
        
        $query = "SELECT a.id as id_aldea,p2.nombre as tipo,  a.nombre, a.coord_x, a.coord_y, tropas.nombre_tropa ,  e.* FROM aldea a, aldea_encole e, tropas, parametrizaciones p2 WHERE  p2.lista = 'TiposAldea'  and p2.nombre not in ('TITULO') and p2.valor = a.tipo and  tropas.cereal > 0 and a.id = e.id_aldea and tropas.id = e.id_tropa and a.id_usuario = ".$idUsu;
        $encole=DB::select($query);
        //print_r($encole);
        $mensaje=$this->obtener_mensaje( $idUsu);
         return  view('aldea.encoles')->with('mensaje',$mensaje)->with('tipo_tropas',$tipo_tropas)->with('encoles',$encole)->with('aldeas',$aldeas);
    }
    public function nuevoencole(request $info)
    {
        $idUsu =auth()->id();
        //$aldeas_usuario = DB::table('aldea')->where('id_cuenta',$idUsu)->get();
        $cantidades = $this->cantidad($info->id_tropa, $info->id_aldea);
        
        
        //print_r($encole);
        $mensaje=$this->obtener_mensaje( $idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@encole');
    }
    public function eliminarencole(request $info)
    {
        $query = "DELETE FROM aldea_encole WHERE id =".$info->idEncole." ;";
        $aldea=DB::select($query);

        return redirect()->action('App\Http\Controllers\Controller_aldeas@encole');
    } 
    public function cantidad($idTropa, $idAldea)
    {
        $idUsu =auth()->id();
        $query = "SELECT velocidad_servidores.tiempo_entreno  as velocidad FROM users, servidor, velocidad_servidores WHERE users.servidor = servidor.id and servidor.velocidad = velocidad_servidores.id and users.id = ".$idUsu;
        $resultado=DB::select($query);
        foreach ($resultado as $a){
            $velocidad =  $a->velocidad;
        }
        //se calcula el bono de alianza
        $reclutamiento = 1;
        /*$query = "SELECT alianzas.reclutamiento FROM users, alianzas where alianzas.id = users.alianza and users.id = ".$idUsu;
        $rec=DB::select($query);
        foreach($rec as $s)
        {
            $reclutamiento = $s->reclutamiento;
        } */
        $madera =0;$madera_g =0;
        $barro =  0;$barro_g =  0;
        $hierro =  0;$hierro_g =  0;
        $cereal =  0;$cereal_g =  0;
        $cantidad_cuartel =0; $cantidad_cuartel_g = 0;
        $cantidad_establo = 0; $cantidad_establo_g  = 0;
        $cantidad_taller  = 0;

       // echo "idAldea:".$idAldea;
        //echo " idTropa:".$idTropa;
        $query = "SELECT cuartel, establo, taller, tiempo FROM `tropas` WHERE id = ".$idTropa;
        $cuartel =  0;
        $establo =  0;
        $taller =  0;
        $tiempo = 0;
        $resultado=DB::select($query);
        foreach ($resultado as $a){
            $cuartel =  $a->cuartel;
            $establo =  $a->establo;
            $taller =  $a->taller;
            $tiempo =  $a->tiempo;
        }
        if ($cuartel == 1) {
            $query = "SELECT cuartel,cuartel_g FROM aldea_edificios where id_aldea = ".$idAldea;
            $resultado=DB::select($query);
            $nivel_cuartel = 0;
            $nivel_cuartel_g =0;
            foreach ($resultado as $a){
                $nivel_cuartel =  $a->cuartel;
                $nivel_cuartel_g =  $a->cuartel_g;
            }
            //echo $nivel_cuartel;
            if($nivel_cuartel>=1){
                $query = "SELECT produccion FROM `construcciones` where nombre_ed = 'Cuartel' and nivel = ".$nivel_cuartel;
                $resultado=DB::select($query);
                $valor_cuartel=1;
                foreach ($resultado as $a){
                    $valor_cuartel =  $a->produccion;
                }

                $cantidad_tropa = (3600/($tiempo*$valor_cuartel))*$velocidad*$reclutamiento;
                $cantidad_cuartel = $cantidad_tropa;
                $query = "SELECT madera*".$cantidad_tropa." as madera, barro*".$cantidad_tropa." as barro, hierro*".$cantidad_tropa." as hierro, cereal*".$cantidad_tropa." as cereal FROM `tropas` WHERE id = ".$idTropa;
                $resultado=DB::select($query);
                foreach ($resultado as $a){
                    $madera = round($a->madera,0,PHP_ROUND_HALF_UP);
                    $barro =  round($a->barro,0,PHP_ROUND_HALF_UP);
                    $hierro =  round($a->hierro,0,PHP_ROUND_HALF_UP);
                    $cereal =  round($a->cereal,0,PHP_ROUND_HALF_UP);
                }
            }
            if($nivel_cuartel_g>=1){
                $query = "SELECT produccion FROM `construcciones` where nombre_ed = 'Cuartel grande' and nivel = ".$nivel_cuartel_g;
                $resultado=DB::select($query);
                foreach ($resultado as $a){
                    $valor_cuartel_grande =  $a->produccion;
                }
                $cantidad_tropagrande = (3600/($tiempo*$valor_cuartel_grande))*$velocidad*$reclutamiento;
                $cantidad_cuartel_g = $cantidad_tropagrande;
                //echo "\n La cantidad grande es: ".$cantidad_tropagrande;
                $query = "SELECT madera*3*".$cantidad_tropagrande." as madera, barro*3*".$cantidad_tropagrande." as barro, hierro*3*".$cantidad_tropagrande." as hierro, cereal*3*".$cantidad_tropagrande." as cereal FROM `tropas` WHERE id = ".$idTropa;
                $resultado=DB::select($query);
                foreach ($resultado as $a){
                    $madera_g =   round($a->madera,0,PHP_ROUND_HALF_UP);
                    $barro_g =  round($a->barro,0,PHP_ROUND_HALF_UP);
                    $hierro_g =  round($a->hierro,0,PHP_ROUND_HALF_UP);
                    $cereal_g =  round($a->cereal,0,PHP_ROUND_HALF_UP);
                }
            }
           // echo "madera:".$madera."#".$madera_g;
        } elseif ($establo == 1) {
            //se busca los niveles del edificio
            $query = "SELECT establo,establo_g FROM aldea_edificios where id_aldea =  ".$idAldea;
            $resultado=DB::select($query);
            $nivel_establo = 0;
            $nivel_establo_g =0;
            foreach ($resultado as $a){
                $nivel_establo =  $a->establo;
                $nivel_establo_g =  $a->establo_g;
            }
            
             //se calcula cantidad por hora
                //normal
                //grande
            if($nivel_establo>=1){
                $query = "SELECT produccion FROM `construcciones` where nombre_ed = 'Establo' and nivel = ".$nivel_establo;
                $resultado=DB::select($query);
                $valor_establo = 1;
                foreach ($resultado as $a){
                    $valor_establo =  $a->produccion;
                }

                $cantidad_tropa = (3600/($tiempo*$valor_establo)*$velocidad)*$reclutamiento;
                $cantidad_establo = $cantidad_tropa;

                //echo "<br> La cantidad es: ".$cantidad_tropa;
                $query = "SELECT madera*".$cantidad_tropa." as madera, barro*".$cantidad_tropa." as barro, hierro*".$cantidad_tropa." as hierro, cereal*".$cantidad_tropa." as cereal FROM `tropas` WHERE id = ".$idTropa;
                $resultado=DB::select($query);
                foreach ($resultado as $a){
                    $madera = round($a->madera,0,PHP_ROUND_HALF_UP);
                    $barro =  round($a->barro,0,PHP_ROUND_HALF_UP);
                    $hierro =  round($a->hierro,0,PHP_ROUND_HALF_UP);
                    $cereal =  round($a->cereal,0,PHP_ROUND_HALF_UP);
                }
            }
            if($nivel_establo_g>=1){
                $query = "SELECT produccion FROM `construcciones` where nombre_ed = 'Establo grande' and nivel = ".$nivel_establo_g;
                $resultado=DB::select($query);
                $valor_establo_grande = 1;
                foreach ($resultado as $a){
                    $valor_establo_grande =  $a->produccion;
                }
                $cantidad_tropagrande = (3600/($tiempo*$valor_establo_grande)*$velocidad)*$reclutamiento;
                $cantidad_establo_g = $cantidad_tropagrande;

                //echo "\n La cantidad grande es: ".$cantidad_tropagrande;
                $query = "SELECT madera*3*".$cantidad_tropagrande." as madera, barro*3*".$cantidad_tropagrande." as barro, hierro*3*".$cantidad_tropagrande." as hierro, cereal*3*".$cantidad_tropagrande." as cereal FROM `tropas` WHERE id = ".$idTropa;
                $resultado=DB::select($query);
                foreach ($resultado as $a){
                    $madera_g =   round($a->madera,0,PHP_ROUND_HALF_UP);
                    $barro_g =  round($a->barro,0,PHP_ROUND_HALF_UP);
                    $hierro_g =  round($a->hierro,0,PHP_ROUND_HALF_UP);
                    $cereal_g =  round($a->cereal,0,PHP_ROUND_HALF_UP);
                }
            }
            //se calcula cantidad por hora
                //normal
                //grande
            //se calculan materias por hora ( normal y grande)
        } elseif ($taller == 1){
             //se busca los niveles del edificio
             $query = "SELECT taller FROM aldea_edificios where id_aldea =  ".$idAldea;
             $resultado=DB::select($query);
             foreach ($resultado as $a){
                 $nivel_taller =  $a->taller;
             }
             
              //se calcula cantidad por hora
                 //normal
                 //grande
             if($nivel_taller>=1){
                 $query = "SELECT produccion FROM `construcciones` where nombre_ed = 'Establo' and nivel = ".$nivel_taller;
                 $resultado=DB::select($query);
                 $valor_taller =1;
                 foreach ($resultado as $a){
                     $valor_taller =  $a->produccion;
                 }
 
                 $cantidad_tropa = (3600/($tiempo*$valor_taller)*$velocidad)*$reclutamiento;
                 $cantidad_taller = $cantidad_tropa;

                 $query = "SELECT madera*".$cantidad_tropa." as madera, barro*".$cantidad_tropa." as barro, hierro*".$cantidad_tropa." as hierro, cereal*".$cantidad_tropa." as cereal FROM `tropas` WHERE id = ".$idTropa;
                 $resultado=DB::select($query);
                 foreach ($resultado as $a){
                     $madera = round($a->madera,0,PHP_ROUND_HALF_UP);
                     $barro =  round($a->barro,0,PHP_ROUND_HALF_UP);
                     $hierro =  round($a->hierro,0,PHP_ROUND_HALF_UP);
                     $cereal =  round($a->cereal,0,PHP_ROUND_HALF_UP);
                 }
             }
         }
        $cantidades = array(
            "idAldea" => $idAldea,
            "idTropa" => $idTropa,
            "cuartel" => floor($cantidad_cuartel),
            "cuartel_g" => floor($cantidad_cuartel_g),
            "establo" => floor($cantidad_establo),
            "establo_g" => floor($cantidad_establo_g),
            "taller" => floor($cantidad_taller),
            "madera" => ($madera_g+ $madera),
            "barro" => ($barro+ $barro_g),
            "hierro" => ($hierro+ $hierro_g),
            "cereal" => ($cereal+ $cereal_g),
        );
        $query = "Insert into aldea_encole(id_aldea,id_tropa,tropa_cuartel,tropa_cuartel_g,tropa_establo,tropa_establo_g,taller,mat_madera,mat_barro,mat_hierro,mat_cereal) values(".$cantidades['idAldea'].",".$cantidades['idTropa'].",".$cantidades['cuartel'].",".$cantidades['cuartel_g'].",".$cantidades['establo'].",".$cantidades['establo_g'].",".$cantidades['taller'].",".$cantidades['madera'].",".$cantidades['barro'].",".$cantidades['hierro'].",".$cantidades['cereal'].")";
        $insert=DB::select($query);
        return $cantidades;
    } 
        
    

}
