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
                foreach ($resultado as $ab){
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

}
