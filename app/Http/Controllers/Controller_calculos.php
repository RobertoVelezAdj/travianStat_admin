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
            $sql = "SELECT mat_madera, mat_barro, mat_hierro, mat_cereal FROM aldea_encole where id_aldea =".$aldea->id;
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
    
    public function npc(){   
        $idUsu =auth()->id();
        $sql = "select c.nivel, c.madera, c.barro, c.hierro, c.cereal, c.consumo, c.consumo, c.pc, c.nombre_ed, c.produccion  FROM construcciones c;";
        $construcciones = DB::select($sql);
         //echo $construcciones;
        $query = "SELECT sum(madera) as madera,sum(barro) as barro,sum(hierro) as hierro,sum(cereal) as cereal FROM aldea a, aldea_producion p WHERE  a.id = p.id_aldea and a.id_usuario  =".$idUsu." ";
        $sa=DB::select($query);
        foreach ($sa as $a){
            $ProduccionMadera =  $a->madera;
            $ProduccionBarro =  $a->barro;
            $ProduccionHierro =  $a->hierro;
            $ProduccionCereal =  $a->cereal;
        }
        $ProducionTotal= 0;
        $ProducionTotal= $ProduccionMadera+$ProduccionBarro+$ProduccionHierro+$ProduccionCereal;

        $query = "SELECT coord_x,coord_y,nombre, madera, barro, hierro, cereal,(madera+ barro+ hierro+ cereal) AS total, 0 as elegido FROM aldea a, aldea_producion p where a.id = p.id_aldea and a.id_usuario  = ".$idUsu." ";
        $ProdAldeas=DB::select($query);


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
            $sql = "SELECT mat_madera, mat_barro, mat_hierro, mat_cereal FROM aldea_encole where id_aldea =".$aldea->id;
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
                "coord_x" => $aldea->coord_x,
                "coord_y" => $aldea->coord_y,
                "nombre" => $aldea->nombre."   (".$aldea->coord_x."/".$aldea->coord_y.")",
                "madera" =>  (int)$madeera_produ - (int)$madera_encole -  (int)$madera_fiesta + (int)$madera_rutas,
                "barro" => (int)$barro_produ -(int)$barro_encole - (int)$barro_fiesta +$barro_rutas,
                "hierro" => (int)$hierro_produ - (int)$hierro_encole - (int)$hierro_fiesta + $hierro_rutas,
                "cereal" => (int)$cereal_produ -(int)$cereal_encole - (int)$cereal_fiesta +$cereal_rutas,
                "total"=>((int)$madeera_produ - (int)$madera_encole -  (int)$madera_fiesta + (int)$madera_rutas)+((int)$barro_produ -(int)$barro_encole - (int)$barro_fiesta +$barro_rutas)+((int)$hierro_produ - (int)$hierro_encole - (int)$hierro_fiesta + $hierro_rutas)+((int)$cereal_produ -(int)$cereal_encole - (int)$cereal_fiesta +$cereal_rutas)
            ];
            //coord_x,coord_y,nombre, madera, barro, hierro, cereal,(madera+ barro+ hierro+ cereal)
            array_push($info_aldeas , $a);
        }
        //print_r($info_aldeas);
         $info_aldea=$info_aldeas; 

        $mensaje=$this->obtener_mensaje( $idUsu);
        return  view('calculos.npc')->with('mensaje',$mensaje)->with('construcciones',$construcciones)->with('ProdAldeas',$info_aldea)->with('ProducionTotal',$ProducionTotal)->with('ProduccionMadera',$ProduccionMadera)->with('ProduccionBarro',$ProduccionBarro)->with('ProduccionHierro',$ProduccionHierro)->with('ProduccionCereal',$ProduccionCereal);

    }
    public function planoff(){
        $idUsu =auth()->id();
        $query = "SELECT a.id as id_aldea, a.coord_x, a.coord_y,a.nombre ,a.tipo, p.nombre as tipo_aldea,a.fiesta_pequena, a.fiesta_grande, ap.madera, ap.barro, ap.hierro,( ap.cereal -c.consumo_total )as cereal,ap.puntos_cultura , e.ayuntamiento FROM aldea a,aldea_producion ap, aldea_edificios e, parametrizaciones p,tiempo_fiestas,consumo_aldeas c WHERE c.id_aldea = a.id and e.ayuntamiento = tiempo_fiestas.nivel_ayuntamiento and p.lista = 'TiposAldea'  and p.nombre not in ('TITULO') and p.valor = a.tipo and  e.id_aldea = a.id and ap.id_aldea = a.id and  a.id_usuario = ".$idUsu;
        $aldeas= DB::select($query);

        $query = "SELECT t.nombre_tropa , t.id FROM tropas t, users u WHERE   t.raza = u.raza and u.id = ".$idUsu." order by t.orden";
        $tipo_tropas= DB::select($query);
        
        $query = "SELECT a.nombre as nombreLanza, a.coord_x as xlanza, a.coord_y as ylanza, i.NombreAldea as nombreRecibe, i.coord_x as xRecibe, i.coord_y as yRecibe, l.distancia, p.nombre_tropa, l.fecha_llegada, l.fecha_lanzamiento FROM lanzamientos l , aldea a, users u, aldea_inac i, servidor s, tropas p where a.id = l.id_aldea_lanza and u.id = l.id_usuario and i.coord_x = l.coord_x_recibe and i.coord_y = l.coord_y_recibe and i.id_server = u.servidor and s.id = u.servidor and i.created_at = s.fch_mod and p.id = l.tropa_lenta and  fecha_lanzamiento > CURRENT_TIMESTAMP() and l.id_usuario = ".$idUsu;
        $ataque= DB::select($query);

        $mensaje=$this->obtener_mensaje( $idUsu);
        return  view('calculos.planoff')->with('mensaje',$mensaje)->with('tipo_tropas',$tipo_tropas)->with('aldeas',$aldeas)->with('ataque',$ataque);
    }

    public function nuevoataque(request $info){
        $idUsu =auth()->id();
        $hora_llegada = $info->dia." ".$info->hora;
        $hora_llegada = $info->dia." ".$info->hora;
        $tz = 'Europe/Madrid';
        $fecha_llegada = new Carbon($hora_llegada);

        $fecha_lanzamiento = new Carbon($hora_llegada);

        $query ="SELECT calcular_distancia2(coord_x,coord_y,".$info->coord_x.",".$info->coord_y.") as distancia, e.p_torneos  FROM aldea a, aldea_edificios e where a.id = e.id_aldea and a.id = ".$info->idAldea;
        $logi=DB::select($query);
        $distancia = 0;
        foreach($logi as $s)
        {
            $distancia = $s->distancia;
            $ptAldea =$s->p_torneos;
        }
       
        
        $query =" SELECT velocidad FROM tropas WHERE id =".$info->idtropa;
        $logi=DB::select($query);
        foreach($logi as $s)
        {
            $velocidad = $s->velocidad;
        }

        $query =" SELECT  * FROM users u WHERE u.id =".$idUsu;
        $logi=DB::select($query);
        foreach($logi as $s)
        {
            $servidor = $s->servidor;
        }

         //2.1 si es menor de 20*/
       /* $velocidad = $info->velocidadVagones;*/
        $velocidad_aux = $velocidad;//*$info->arteoff;
        if($distancia <=20){
            //sin pt
            $tiempo_restante =  ($distancia/($velocidad_aux)*3600);
            
        }else{
            //echo "|velocidad_aux:".$velocidad_aux."|";
            $tiempo_restante = round(20/($velocidad_aux)*3600);
            $velocidad_aux = $velocidad_aux* (1+($ptAldea*0.2)+$info->heroe_botas);
            $distancia_aux =$distancia-20;
            $tiempo_restante = round($tiempo_restante+ floor($distancia_aux/($velocidad_aux)*3600));
            /*Después de los primeros 20 campos: velocidad base * 2 (bonificación por artefacto) * 1,2 (bonificación estandartes) * (1 + 0,25 (botas) + 2 (plaza del torneo)) = velocidad base * 5,65*/
        
        }
        $horas = floor($tiempo_restante / 3600);
        $minutos = floor(($tiempo_restante - ($horas * 3600)) / 60);
        $segundos = round($tiempo_restante - ($horas * 3600) - ($minutos * 60));
   
        //2.2 si es mayor de 20
        $fecha_lanzamiento->subHours($horas);
        $fecha_lanzamiento->subMinute($minutos);
        $fecha_lanzamiento->subSecond($segundos);
        $query ="SELECT calcular_distancia(coord_x,coord_y,".$info->coord_x.",".$info->coord_y.") as distancia, e.p_torneos  FROM aldea a, aldea_edificios e where a.id = e.id_aldea and a.id = ".$info->idAldea;
        $logi=DB::select($query);
        $distancia = 0;
        foreach($logi as $s)
        {
            $distancia = $s->distancia;
            $ptAldea =$s->p_torneos;
        }
        /*echo "Velocidad:".$velocidad_aux."|";
        echo "Distancia:".$distancia."|";
        echo "fecha llegada:".$fecha_llegada."|";
        echo "tiempo:".$horas.":".$minutos.":".$segundos."|"."lanzamiento".$fecha_lanzamiento;*/
        $query ="INSERT INTO lanzamientos(servidor, id_aldea_lanza, coord_x_recibe, coord_y_recibe, fecha_llegada, fecha_lanzamiento, distancia, id_usuario,tropa_lenta) VALUES ('".$servidor."','".$info->idAldea."',".$info->coord_x.",".$info->coord_y.",'".$fecha_llegada."','".$fecha_lanzamiento."','".$distancia."','".$idUsu."',".$info->idtropa.")";
        $ataque= DB::select($query);


        /////Envio mensaje nuevo usuario
        $mensaje = "Ataque próximo, por favor verifique el plan ofensivo";
        $link='travianstat.es/login';
        $fecha_lanzamiento = $fecha_lanzamiento->Hour(2);
        $fecha_lanzamiento = $fecha_lanzamiento->Minute(5);
            $minuto = $fecha_lanzamiento->minute;
            $hora = $fecha_lanzamiento->hour;
            $dia = $fecha_lanzamiento->day;
            $mes = $fecha_lanzamiento->month;
            $ano = $fecha_lanzamiento->year;
            
       
            
            
        $query = "INSERT INTO notificaciones_telegram( id_usuario, texto, link, `ano`, `mes`, `dia`, `hora`, `minuto`, `enviado`) VALUES ('7','".$mensaje."','".$link."','".$ano."','".$mes."','".$dia."','".$hora."','".$minuto."','0')";
        $aldea=DB::select($query);


        return redirect()->action('App\Http\Controllers\Controller_calculos@planoff');
    }
   
}
