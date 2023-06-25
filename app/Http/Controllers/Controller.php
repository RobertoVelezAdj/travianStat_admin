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
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
     
    public function creacion_mensaje($tipo, $mensaje,$usuario){
      
        $query2 = "INSERT INTO notificaciones_emergentes(usuario,tipo,mensaje,estado,created_at)values(".$usuario.",'".$tipo."','".$mensaje."','0',current_date())";
        $aux2= DB::select($query2);
    }
    public function obtener_mensaje($usuario){
        $mensaje="";
        $query = "SELECT * FROM notificaciones_emergentes WHERE  estado = '0' and usuario = ".$usuario;
        $aux= DB::select($query);
        foreach ($aux as $a){
            $mensaje = "Toast.fire({
                icon: '".$a->tipo."',
                title: '".$a->mensaje."'
                });";   
                $query2 = "UPDATE notificaciones_emergentes SET estado='1' WHERE id =  ".$a->id;
                $aux2= DB::select($query2);
         }
         
        return $mensaje;
    }
    public function paso_mensaje($tipo, $mensaje,$usuario){
      
        $query2 = "INSERT INTO notificaciones_emergentes(usuario,tipo,mensaje,estado,created_at)values(".$usuario.",'".$tipo."','".$mensaje."','2',current_date())";
        $aux2= DB::select($query2);
    }
    public function obtener_lista($usuario){
        $lista="";
        $query = "SELECT * FROM notificaciones_emergentes WHERE  estado = '2' and usuario = ".$usuario;
        $aux= DB::select($query);
        foreach ($aux as $a){
            $lista = $a->tipo;   
            $query2 = "UPDATE notificaciones_emergentes SET estado='1' WHERE id =  ".$a->id;
            $aux2= DB::select($query2);
         }
        return $lista;
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
        $query = "SELECT alianzas.reclutamiento FROM users, alianzas where alianzas.id = users.alianza and users.id = ".$idUsu;
        $rec=DB::select($query);
        foreach($rec as $s)
        {
            $reclutamiento = $s->reclutamiento;
        } 
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
