<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class Controller_vacas extends Controller
{
    public function __construct()
    {
        
        $this->middleware('auth');
    }
    public function inicio()
    {
        $idUsu =auth()->id();
        $aldeas_usuario = DB::table('aldea')->where('id_usuario',$idUsu)->get();
        //print_r ($aldeas_usuario);
        $vacio ='no';

        $mensaje=$this->obtener_mensaje( $idUsu);
        return  view('vacas.index')->with('mensaje',$mensaje)->with('aldeas',$aldeas_usuario)->with('estado',$vacio);
    } 
  
    public function actualizar_pago(request $info)
    {
        $idUsu =auth()->id() ;

        $vowels = array("select", "query", "insert", "update","‭");
        $cadena_limpia = str_replace($vowels, "", $info->madera);
        $cadena = explode(" 	", $cadena_limpia);

       // print_r($cadena);
        $nombre_aldea = 0;
        $poblacion = 1;
        $distancia = 2;
        $tropa = 3;
        $result = 4;
        for($i = 0; $i < sizeof($cadena);$i=$i+6)
        {
           // echo "". ."/".$cadena[$poblacion]."/".$cadena[$distancia]."/".$cadena[$tropa]."/".$cadena[$resultado]."\n";
            $query = "select a.id as id_aldea, ina.idAldea as aldeaVaca, ina.coord_x, ina.coord_y, ina.poblacion, ina.NombreAldea, s.id as idServer from aldea_inac ina, servidor s, users u, aldea a where a.id_usuario = u.id and ina.NombreAldea = '".trim($cadena[$nombre_aldea])."' and calcular_distancia(a.coord_x,a.coord_y,ina.coord_x,ina.coord_y) = ".$cadena[$distancia]." and u.servidor = s.id and s.fch_mod = ina.created_at and a.id  =".$info->id_aldea;
            $resultado= DB::select($query);
            foreach ($resultado as $a){
               
            //se mira si esta en listas de vacas y si no... se inserta
                $query2 = "SELECT * FROM lista_vacas l where l.IdAldea = ".$a->id_aldea." and l.IdServer = ".$a->idServer." and l.IdAldeaVaca = ".$a->aldeaVaca;
                $resultado2= DB::select($query2);
                $aux = 0;
                foreach ($resultado2 as $ab){
                    $aux = 1;
                }
                //si no existe se inserta
                 if($aux==0){
                    //se inserta la nueva vaca
                    $query3 ="INSERT INTO lista_vacas(IdAldea,IdServer,IdAldeaVaca) VALUES ('".$a->id_aldea."','".$a->idServer."','".$a->aldeaVaca."')";
                    $resultado3= DB::select($query3);
                 }

            }          
            $nombre_aldea=$nombre_aldea+6;
            $poblacion = $poblacion+6;
            $distancia = $distancia+6;
            $tropa = $tropa +6;
            $result = $result+6; 
        }

        $aux=$this->creacion_mensaje('success', "Vacas almacenadas de forma correcta.",$idUsu);
        return redirect()->action('App\Http\Controllers\Controller_vacas@inicio');
    }
    public function calculovacas(request $info)
    {
        
        $busqueda = [
            "aldeaLanza" => $info->idAldea,
            "dias" => $info->dias,
            "distancia" => $info->distancia,
            "cambiopob" => $info->poblacion,
            "minpob" =>$info->pobAldeas,
            "PerdidaPobl"=>$info->PerdidaPobl,
            "sinAlianza"=>$info->sinAlianza,
        ];

        
        $idUsu =auth()->id();
        $aldeas_usuario = DB::table('aldea')->where('id_USUARIO',$idUsu)->get();
        $dias = ($info->dias)-1;
        $info_usu = DB::table('users')->select('servidor')->where('id',$idUsu)->get();
        $vacio ='si';

        $filtrosPObNegativo= "";
        if($info->PerdidaPobl==1){
            $filtrosPObNegativo= " and (hoy - poblacion_antes) >-1 ";
        }
        
        $filtroSinAlianza = "";
        if($info->sinAlianza==1){
            $filtroSinAlianza= "and cuenta_inac.IdAlianza=0 ";
        }
          foreach($info_usu as $s)
        {
            $idserver = $s->servidor;
        }
          $query = "SELECT distinct
                distancia,
                NombreAldea as nombrealdea,
                rutaServer,
                coord_x,
                coord_y,result.raza, cuenta_inac.IdAlianza as idAlianza,     (
            SELECT
                a2.NombreAlianza
            FROM
                alianza_inac a2
            WHERE
                a2.id =(
                SELECT
                    MAX(id)
                FROM
                    alianza_inac a1
                WHERE
                    a1.IdAlianza = cuenta_inac.IdAlianza AND a1.id_Server = result.id_server
            )
        ) AS NombreAlianza, id_aldea,
                cuenta_inac.idCuenta, cuenta_inac.NombreCuenta,
                poblacion_antes, hoy, poblacion as poblacion_aldea, hoy-poblacion_antes as dif_poblacion_cuenta
            FROM
                (
                SELECT
                    calcular_distancia(
                        (
                        SELECT
                            coord_x
                        FROM
                            aldea
                        WHERE
                            id = ".$info->idAldea."
                    ),
                    (
                    SELECT
                        coord_y
                    FROM
                        aldea
                    WHERE
                        id = ".$info->idAldea."
                ),
                vista_vacas.coord_x,
                vista_vacas.coord_y
                    ) AS distancia,
                    (
                    SELECT
                        SUM(poblacion)
                    FROM
                        aldea_inac
                    WHERE
                        vista_vacas.IdCuenta = aldea_inac.IdCuenta AND created_at = DATE_ADD(
                            vista_vacas.fecha,
                            INTERVAL - ".$dias." DAY
                        ) and aldea_inac.id_server = vista_vacas.id_server
                ) AS poblacion_antes,
                vista_vacas.*
            FROM
                `vista_vacas`
            WHERE
                vista_vacas.id_server = ".$idserver."
            ) result,
            cuenta_inac
            WHERE
                hoy-poblacion_antes <= ".$info->poblacion." ".$filtrosPObNegativo." ".$filtroSinAlianza." and poblacion >= ".$info->pobAldeas."  and result.idcuenta = cuenta_inac.idCuenta AND cuenta_inac.IdServer = result.id_server AND distancia <= ".$info->distancia." 
                aND id_aldea NOT IN(
                    SELECT
                        idAldeaVaca
                    FROM
                        `lista_vacas`
                    WHERE
                        lista_vacas.IdAldea = ".$info->idAldea." AND idServer =  result.id_server 
                    
                )  
            ORDER BY `distancia` ASC";
       // echo $query;
        $aldeas=DB::select($query);
        //print_r ($info);
        $mensaje=$this->obtener_mensaje( $idUsu);
        return  view('vacas.index')->with('mensaje',$mensaje)->with('info',$aldeas)->with('estado',$vacio)->with('aldeas',$aldeas_usuario)->with('busqueda',$busqueda);
    } 
    
    public function listaVacas()
    {
        $idUsu =auth()->id() ;
        $query = 'SELECT distinct aldea_inac.poblacion, servidor.ruta,aldea.nombre as nombreLanza,cuenta_inac.IdCuenta as idcuentavaca, alianza_inac.IdAlianza AS alivaca,  aldea_inac.NombreAldea as nombrealdeaVaca,  aldea.coord_x as aldeaLanzax, aldea_inac.coord_x as vacax, aldea_inac.coord_y as vacay, aldea.coord_y as aldeaLanzay,cuenta_inac.NombreCuenta as cuentaVaca,alianza_inac.NombreAlianza as alianzaVaca, lista_vacas.created_at FROM `lista_vacas`, servidor, users, aldea, aldea_inac, cuenta_inac, alianza_inac
        where  lista_vacas.IdServer = servidor.id
        and users.servidor = servidor.id
        and aldea.id_cuenta = users.id
        and aldea.id = lista_vacas.IdAldea
        and aldea_inac.id_server = servidor.id
        and aldea_inac.idAldea = lista_vacas.IdAldeaVaca
        and aldea_inac.created_at = servidor.fch_mod
        and cuenta_inac.IdCuenta = aldea_inac.IdCuenta
        and cuenta_inac.IdServer = servidor.id
        and alianza_inac.id_Server = servidor.id
        and alianza_inac.IdAlianza = cuenta_inac.IdAlianza
        and users.id = '.$idUsu;

        $info=DB::select($query);
      //  print_r ($info);
        return  view('LVacas')->with('info',$info);
    }
 
    
    public function insertarVacas(request $info)
    {
        $idUsu =auth()->id();
        $info_usu = DB::table('users')->select('servidor')->where('id',$idUsu)->get();
        foreach($info_usu as $s)
       {
           $idserver = $s->servidor;
       }
        $sql = "INSERT INTO `lista_vacas` ( `IdAldea`, `IdServer`, `IdAldeaVaca`, `created_at`, `updated_at`) VALUES ( '".$info->idAldea."', '".$idserver."', '".$info->idAldeaVaca."', current_date(), current_date())";
        $resultado=DB::select($sql);
       
        $busqueda = [
            "aldeaLanza" => $info->idAldea,
            "dias" => $info->dias,
            "distancia" => $info->distancia,
            "cambiopob" => $info->poblacion,
            "minpob" =>$info->pobAldeas,
            "PerdidaPobl"=>$info->PerdidaPobl,
            "sinAlianza"=>$info->sinAlianza,
        ];

 
         $aldeas_usuario = DB::table('aldea')->where('id_cuenta',$idUsu)->get();
        $dias = ($info->dias)-1;
         $vacio ='si';

        $filtrosPObNegativo= "";
        if($info->PerdidaPobl==1){
            $filtrosPObNegativo= " and (hoy - poblacion_antes) >-1 ";
        }
        
        $filtroSinAlianza = "";
        if($info->sinAlianza==1){
            $filtroSinAlianza= "and cuenta_inac.IdAlianza=0 ";
        }
          $query = "SELECT distinct
                distancia,
                NombreAldea as nombrealdea,
                rutaServer,
                coord_x,
                coord_y,result.raza, cuenta_inac.IdAlianza as idAlianza,     (
            SELECT
                a2.NombreAlianza
            FROM
                alianza_inac a2
            WHERE
                a2.id =(
                SELECT
                    MAX(id)
                FROM
                    alianza_inac a1
                WHERE
                    a1.IdAlianza = cuenta_inac.IdAlianza AND a1.id_Server = result.id_server
            )
        ) AS NombreAlianza, id_aldea,
                cuenta_inac.idCuenta, cuenta_inac.NombreCuenta,
                poblacion_antes, hoy, poblacion as poblacion_aldea, hoy-poblacion_antes as dif_poblacion_cuenta
            FROM
                (
                SELECT
                    calcular_distancia(
                        (
                        SELECT
                            coord_x
                        FROM
                            aldea
                        WHERE
                            id = ".$info->idAldea."
                    ),
                    (
                    SELECT
                        coord_y
                    FROM
                        aldea
                    WHERE
                        id = ".$info->idAldea."
                ),
                vista_vacas.coord_x,
                vista_vacas.coord_y
                    ) AS distancia,
                    (
                    SELECT
                        SUM(poblacion)
                    FROM
                        aldea_inac
                    WHERE
                        vista_vacas.IdCuenta = aldea_inac.IdCuenta AND created_at = DATE_ADD(
                            vista_vacas.fecha,
                            INTERVAL - ".$dias." DAY
                        ) and aldea_inac.id_server = vista_vacas.id_server
                ) AS poblacion_antes,
                vista_vacas.*
            FROM
                `vista_vacas`
            WHERE
                vista_vacas.id_server = ".$idserver."
            ) result,
            cuenta_inac
            WHERE
                hoy-poblacion_antes <= ".$info->poblacion." ".$filtrosPObNegativo." ".$filtroSinAlianza." and poblacion >= ".$info->pobAldeas."  and result.idcuenta = cuenta_inac.idCuenta AND cuenta_inac.IdServer = result.id_server AND distancia <= ".$info->distancia." 
                aND id_aldea NOT IN(
                    SELECT
                        idAldeaVaca
                    FROM
                        `lista_vacas`
                    WHERE
                        lista_vacas.IdAldea = ".$info->idAldea." AND idServer =  result.id_server 
                    
                )  
            ORDER BY `distancia` ASC";
       // echo $query;
        $aldeas=DB::select($query);
        //print_r ($info);
        $mensaje=$this->obtener_mensaje( $idUsu);
        return  view('vacas.index')->with('mensaje',$mensaje)->with('info',$aldeas)->with('estado',$vacio)->with('aldeas',$aldeas_usuario)->with('busqueda',$busqueda);
    }
}
