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

use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;

 class Controller_aldeas extends Controller{
    
    public function index(){   
       
        $idUsu =auth()->id();
        $usu = User::whereId($idUsu)->first(); 
        if($usu->hasPermissionTo('Administrador')){
            return redirect()->action('App\Http\Controllers\Controller_admin_permisos@index');
        }

        if($usu->hasPermissionTo('Apuestas')){
            return redirect()->action('App\Http\Controllers\Controller_admin_apuestas@Abiertas');
        }
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
        $query = "SELECT alianzas.filosofia FROM users, alianzas where alianzas.id = users.alianza and users.id = ".$idUsu;
        $filo=DB::select($query);
        foreach($filo as $s)
        {
            $filosofia = $s->filosofia;
        } 
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
        $vowels = array("select", "query", "insert", "update","‭","‬");
        $cadena_limpia = str_replace($vowels, "", $info->madera);
        $cadena_limpia = str_replace("\t", " ", $cadena_limpia);

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
       // print_r($cadena);
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
                //echo $query;
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

        $aux=$this->creacion_mensaje('success', "Tropas de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@mistropas');
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
        $vowels = array("select", "query", "insert", "update","‭","‬");
        $cadena_limpia = str_replace($vowels, "", $info->madera);
        $cadena_limpia = str_replace("\t", " ", $cadena_limpia);

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
                //echo $query;
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
        return redirect()->action('App\Http\Controllers\Controller_aldeas@index');
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
    public function Reportarataque(request $info)
    {
        $idUsu =auth()->id();
        $date = Carbon::now();
        $hora_llegada = $info->diaImpacto." ".$info->horaImpacto;
      
        $tz = 'Europe/Madrid';
        $fecha_llegada = new Carbon($hora_llegada);
        
        //1.- Distancia
        $query ="SELECT calcular_distancia2(coord_x,coord_y,".$info->coord_x.",".$info->coord_y.") as distancia, e.p_torneos  FROM aldea a, aldea_edificios e where a.id = e.id_aldea and a.id = ".$info->idAldea;
        $logi=DB::select($query);
        
        $distancia = 0;
        
        foreach($logi as $s)
        {
            $distancia = $s->distancia;
        }
        $pendiente = explode(":",$info->horaMin);
        $Sinentrar = explode(":",$info->horaMax);
        $SegPendiente = $pendiente[0]*3600+$pendiente[1]*60+$pendiente[2];
        $SegSinentrar = $Sinentrar[0]*3600+$Sinentrar[1]*60+$Sinentrar[2];
        $SegTotal = $SegPendiente+$SegSinentrar;
        
        
        $velocidad = 3;
        $velocidad_aux = $velocidad*$info->arteoff;
        $catas =0;
        for ($i = 1; $i<21; $i++) {
            if($distancia <=20){
                //sin pt
                $tiempo_restante = ($distancia/($velocidad_aux)*3600);
               
            }else{
    
                $tiempo_restante = floor(20/($velocidad_aux)*3600);
                $velocidad_aux = $velocidad_aux* (1+($i*0.2)+$info->heroe_botas);
                $distancia_aux =$distancia-20;
                $tiempo_restante = $tiempo_restante+ floor($distancia_aux/($velocidad_aux)*3600);
    
            }
            if($tiempo_restante <$SegTotal ){
                $catas = 1;
            }
        }
       
         $lanzamientoMax =$date->subSecond($SegTotal);
        $idAldeaatacante =$date->subSecond($SegPendiente);

        $query ="select nombre_cuenta,users.alianza, aldea.nombre, aldea.coord_x, aldea.coord_y, aldea.tipo, users.servidor from users, aldea where aldea.id_usuario= users.id  and aldea.id = ".$info->idAldea;
        $logi=DB::select($query);
        foreach($logi as $s)
        {
            $login = $s->nombre_cuenta;
            $idAli = $s->alianza;
            $aldea = "".$s->nombre." (".$s->coord_x."/".$s->coord_y.") ".$s->tipo;
            $servidor = $s->servidor;
        } 
        
        $query ="SELECT a.NombreAldea, a.coord_x, a.coord_y, c.NombreCuenta,  ai.NombreAlianza, c.IdCuenta as idCuenta, a.idAldea as idaldea FROM aldea_inac a, cuenta_inac c, servidor s, alianza_inac ai WHERE s.id = a.id_server and a.coord_x = ".$info->coord_x." and a.coord_y = ".$info->coord_y." and s.id = ".$servidor." and s.fch_mod = a.created_at and c.IdCuenta = a.IdCuenta and c.IdServer = s.id  and ai.IdAlianza = c.IdAlianza and ai.id_Server = s.id;";
        $logi=DB::select($query);
        $idCuenta=0;
        $idAldeaatacante=0;
        $atacante ='';
        foreach($logi as $s)
        {
            $idAldeaatacante = $s->idaldea;
            $idCuenta = $s->idCuenta;
            $atacante = $s->NombreCuenta. " desde ".$s->NombreAldea."(".$s->coord_x."/".$s->coord_y.")";
        } 
        
        ///
            //Insertar el heroe
            //ser revisa si ya está reportado
            $query ="SELECT count(*) as existe FROM heroe WHERE id_cuenta= ".$idCuenta." and id_Server = ".$servidor;
            $logi=DB::select($query);
            $existe = 0;
            foreach($logi as $s)
            {
              $existe =  $s->existe;
              
                
            }
            if($existe==1){
                //se revisa si se tienen cambios
                $query ="SELECT count(*) as existe FROM heroe WHERE id_cuenta= ".$idCuenta." and id_Server = ".$servidor." and link ='".$info->link_heroe."' and id_alianza=".$idAli ;
                $logi=DB::select($query);
                $cambios = 0;
                foreach($logi as $s)
                {
                   
                    $cambios = $s->existe;
                     
                }
                if($cambios==0){
                    $query ="select count(*) as contador from ataque where ataque.id_aldea_at in (select   aldea_inac.idaldea from cuenta_inac,aldea_inac,servidor where cuenta_inac.IdCuenta = ".$idCuenta." and aldea_inac.IdCuenta = cuenta_inac.IdCuenta and cuenta_inac.IdServer = aldea_inac.id_server and servidor.id = cuenta_inac.IdServer and servidor.fch_mod = aldea_inac.created_at and cuenta_inac.IdServer = ".$servidor.") and ataque.id_alianza = ".$idAli." and ataque.llegada >'".$date->toDateTimeString()."'";
                    $logi=DB::select($query);
                    $existe = 0;
                    foreach($logi as $s)
                    {
                        $contador =  $s->contador;
                    //echo "existe : ".$existe;
                        
                    }
                    if($contador ==0){
                        $query ="UPDATE heroe SET link='".$info->link_heroe."',fecha_cambio=current_timestamp() WHERE id_cuenta= ".$idCuenta." and id_Server = ".$servidor;
                    }else{
                        $query ="UPDATE heroe SET link='".$info->link_heroe."',fecha_cambio=current_timestamp() WHERE id_cuenta= ".$idCuenta." and id_Server = ".$servidor;
                    }
                
                    $logi=DB::select($query);
                }     
            }else{
                 $query ="INSERT INTO heroe(id_cuenta,link,id_server,id_alianza) VALUES (".$idCuenta.",'".$info->link_heroe."',".$servidor.",".$idAli.")";
                $logi=DB::select($query);
            }
            

        ///INSERT EN ATAQUE
        $query= "INSERT INTO ataque( id_aldea_deff, id_aldea_at, llegada, id_alianza,vagones,intercalada) VALUES (".$info->idAldea.",".$idAldeaatacante.",'".$fecha_llegada."',".$idAli.",".$info->Nvagones.",'".$info->intercalada."')";
        $q=DB::select($query);
        /////
            
        $mensaje = "El jugador ".$login." está recibiendo un ataque en la aldea ".$aldea." por el  jugador ".$atacante." con hora de llegada ".$fecha_llegada;
        
        $query ="SELECT YEAR(NOW()) as ano,MONTH (NOW())as mes ,DAY(NOW()) as dia,DATE_FORMAT(NOW( ), '%H' ) as hora,DATE_FORMAT(NOW( ), '%i' ) +2 as minuto FROM dual;";
        $q=DB::select($query);
        foreach($q as $s)
        {
            $minuto = $s->minuto;
            $hora = $s->hora;
            $dia = $s->dia;
            $mes = $s->mes;
            $ano = $s->ano;
            if($minuto>59){
                $minuto = $minuto -60; 
                $hora = $hora +1;
            }
            if($hora >23){
                $hora = $hora-24;
                $dia = $dia +1;
            }
            if($dia> cal_days_in_month(CAL_GREGORIAN, $mes, $ano)){
                $dia = $dia - cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
                $mes = $mes +1;
            }
            if($mes >12){
                $mes = $mes-12;
                $ano = $ano +1;
            }
        } 
        $link = 'travianstat.es/reporteAtaquesAli';
        $idUsu =auth()->id();
        $users = DB::table('users')->where('alianza', '=', $idAli)->get();
        foreach($users as $usuario)
        {
            $usu = User::whereId($usuario->id)->first(); 
            
            if($usu->hasPermissionTo('ministro_deff')==1){
                $query = "INSERT INTO notificaciones_telegram( id_usuario, texto, link, `ano`, `mes`, `dia`, `hora`, `minuto`, `enviado`) VALUES ('".$usu->id."','".$mensaje."','".$link."','".$ano."','".$mes."','".$dia."','".$hora."','".$minuto."','0')";
                $aldea=DB::select($query);
            }
        }

        
        //print_r($encole);
        $mensaje=$this->obtener_mensaje( $idUsu);
        return redirect()->action('App\Http\Controllers\Controller_aldeas@index');
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
    
    public function actualizarNombres()
    {
        
        $query6 =  "select u.nombre_cuenta, u.servidor as id_server, u.id as id_usuario from users u";
        $resultado6=DB::select($query6);
        foreach ($resultado6 as $a){
            $query2 = "select i.* from cuenta_inac c, aldea_inac i , servidor s where s.estado = 0 and i.IdCuenta = c.IdCuenta and i.id_server = s.id and s.fch_mod = i.updated_at and c.NombreCuenta ='".$a->nombre_cuenta."' and s.id = ".$a->id_server;
            $resultado2=DB::select($query2);
            foreach ($resultado2 as $a2){
                $contador= 0;
                //cuento si existe la aldea
                $query3 =  "SELECT a.id, a.nombre FROM aldea  a WHERE  a.coord_x = ".$a2->coord_x." and a.coord_y =  ".$a2->coord_y." and a.id_usuario = ".$a->id_usuario;
                $resultado3=DB::select($query3);
                foreach ($resultado3 as $a3){
                    //si existe update nonbre
                    if($a2->NombreAldea !== $a3->nombre){
                        $query4 = "update aldea a set a.nombre = '".$a2->NombreAldea."'where a.id = ".$a3->id;
                        $resultado4=DB::select($query4);
                    }
                    $contador = 1;
                }
                if( $contador<1) {
                    //se crea la aldeaal usuario
                    $query = "INSERT INTO aldea(id_usuario,coord_x,coord_y,nombre,tipo,fiesta_pequena,fiesta_grande,created_at)VALUES(".$a->id_usuario.",".$a2->coord_x.",".$a2->coord_y.",'".$a2->NombreAldea."',8,0,0,current_timestamp())";
                    $tipos= DB::select($query);
            
                    $query = "SELECT max(id) as id FROM aldea WHERE id_usuario = ".$a->id_usuario;
                    $r=DB::select($query);
                    foreach ($r as $a){
                        $id_aldea =  $a->id;
                    }
                    //con el id.. registro en edificios y produccion
                    $query = "INSERT INTO aldea_edificios(id_aldea)VALUES(".$id_aldea.")";
                    $r= DB::select($query);
                    $query = "INSERT INTO aldea_tropas(id_aldea)VALUES(".$id_aldea.")";
                    $r= DB::select($query);
                    $query = "INSERT INTO aldea_producion(id_aldea)VALUES(".$id_aldea.")";
                    $r= DB::select($query);
                }
            
                
            }
        }
    } 
    

}
