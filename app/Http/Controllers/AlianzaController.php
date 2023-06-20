<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class AlianzaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $alianza = [];
        $idUsu =auth()->id();
        $query = "SELECT id, alianza, nvl(alianza,'NO') as tiene FROM users WHERE ID = ".$idUsu;
        $t=DB::select($query);
        foreach($t as $s)
        {
            $tiene = $s->tiene;
            if($tiene ==0){
                $tiene= 'NO';
            }
        }
        if($tiene<>'NO'){
            $query = "SELECT * FROM alianzas where id = (select alianza from users where id = ".$idUsu.")";
            $alianza=DB::select($query);  
        }
        $resultado = 1;
        $query = "SELECT alianzas.*, peticiones_aldea.id, peticiones_aldea.fecha FROM peticiones_aldea, alianzas WHERE alianzas.id = peticiones_aldea.id_alianza and id_usuario = ".$idUsu;
        $peticiones=DB::select($query);

        $query = "SELECT valor,nombre FROM parametrizaciones WHERE  lista = 'Bonometalurgia' order by valor";
        $metalurgia=DB::select($query);

        $query = "SELECT valor,nombre FROM parametrizaciones WHERE  lista = 'Bonoreclutamiento' order by valor";
        $reclutamiento=DB::select($query);

        $query = "SELECT valor,nombre FROM parametrizaciones WHERE  lista = 'Bonofilosofia' order by valor";
        $filosofia=DB::select($query);

        $query = "SELECT valor,nombre FROM parametrizaciones WHERE  lista = 'Bonocomercio' order by valor";
        $comercio=DB::select($query);
        
        return  view('alianza.datosAlianza')->with('tiene_alianza',$tiene)->with('alianza',$alianza)->with('resultado',$resultado)->with('peticiones',$peticiones)->with('comercio',$comercio)->with('filosofia',$filosofia)->with('reclutamiento',$reclutamiento)->with('metalurgia',$metalurgia);
    }
    public function crearAli(request $info){
        $idUsu =auth()->id();
        $query = "SELECT servidor FROM users WHERE ID = ".$idUsu;
        $t=DB::select($query);
        $id_ali = -1;
        foreach($t as $s)
        {
            $server = $s->servidor;
        }
        $query = "SELECT id FROM alianzas WHERE nombre = '".$info->nombre."' and id_server =".$server;
        $t=DB::select($query);
        foreach($t as $s)
        {
            $id_ali = $s->id;
        }
        //echo $id_ali;
        if($id_ali==-1)
        {
             
            //La alianza no existe
            //se crea la alianza
            $query = "INSERT INTO alianzas (id_server,nombre) values(".$server.",'".$info->nombre."');";
            $t=DB::select($query);
            //se inserta este update en el usuario (id_aliaza)
            $query = "SELECT id FROM alianzas WHERE nombre = '".$info->nombre."' and id_server =".$server;
            $t=DB::select($query);
            foreach($t as $s)
            {
                $id_ali = $s->id;
            }
            $query = "UPDATE users SET alianza = ".$id_ali." WHERE id = ".$idUsu;
            $t=DB::select($query);
            //se dan permisos al usuario
            $user= auth()->user(); 
            $user->givePermissionTo('lider_alianza');
            $user->givePermissionTo('soldado_raso');
            return redirect()->action('App\Http\Controllers\AlianzaController@index');
        }else{
            //echo "existe";
            $query = "SELECT id, alianza, nvl(alianza,'NO') as tiene FROM users WHERE ID = ".$idUsu;
            $t=DB::select($query);
            foreach($t as $s)
            {
                $tiene = $s->tiene;
            }
            if($tiene<>'NO'){
                $query = "SELECT * FROM alianzas where id = (select alianza from users where id = ".$idUsu.")";
                $alianza=DB::select($query);  
            }
            $resultado = 0;
            return  view('alianza.datosAlianza')->with('tiene_alianza',$tiene)->with('alianza',$alianza)->with('resultado',$resultado);
        }     
    }
    public function editarAli(request $info){
        
        $query = "UPDATE alianzas SET nombre = '".$info->nombre."',metalurgia = '".$info->metalurgia."',reclutamiento = '".$info->reclutamiento."' ,filosofia = '".$info->filosofia."' ,comercio = '".$info->comercio."'  WHERE id =". $info->idAli;
        $t=DB::select($query);
        return redirect()->action('App\Http\Controllers\AlianzaController@index');
    }
    
    public function gestionUsuarios(request $info){
        $usuarios = [];
        $idUsu =auth()->id();
        $users = DB::table('users')->get();
        $todos_permisos = "";
        $query = "SELECT alianza from users where id =".$idUsu ;
        $t=DB::select($query);
        foreach($t as $s)
        {
            $alianzausu = $s->alianza;
        }
         foreach($users as $usuario)
        {
            $usu = User::whereId($usuario->id)->first(); 
            
            if($usu->alianza==$alianzausu){
                array_push($usuarios , $usu);
            }
        }
        $query = "SELECT valor FROM parametrizaciones WHERE  lista = 'PermisosAlianzas' and valor <>'TITULO';";
        $todos_permisos=DB::select($query);
        
        $query = "SELECT users.email,peticiones_aldea.fecha, peticiones_aldea.id FROM peticiones_aldea, users WHERE  users.id = id_usuario;";
        $pendientes=DB::select($query);

        return  view('alianza.alianza')->with('usuarios',$usuarios)->with('permisos',$todos_permisos)->with('pendientes',$pendientes);
    }
    public function AnadirPermiso(request $info)
    {
        $userpermiso = User::whereId($info->idUsu)->first(); 
        $userpermiso->givePermissionTo($info->permiso);
        return redirect()->action('App\Http\Controllers\AlianzaController@gestionUsuarios');
    }
    public function EliminarPermiso(request $info)
    {
        $userpermiso = User::whereId($info->idUsu)->first(); 
        $userpermiso->revokePermissionTo($info->permiso);
        return redirect()->action('App\Http\Controllers\AlianzaController@gestionUsuarios');
    }
    public function anadirUsu(request $info){
        
        $idUsu =auth()->id();
        $query = "SELECT id from users where email = '".$info->correo."'";
        $t=DB::select($query);
        foreach($t as $s)
        {
                $idusunuevo = $s->id;
        }
        $query = "SELECT alianza from users where id =".$idUsu ;
        $t=DB::select($query);
            foreach($t as $s)
            {
                $alianzausu = $s->alianza;
            }
        $query = "INSERT INTO peticiones_aldea(id_alianza,id_usuario) VALUES(".$alianzausu.",".$idusunuevo.")";
        $t=DB::select($query);
        return redirect()->action('App\Http\Controllers\AlianzaController@gestionUsuarios');
    }
    public function eliminarPeticion(request $info){
        
        $query ="DELETE FROM peticiones_aldea WHERE id = ".$info->id;
        $t=DB::select($query);
        return redirect()->action('App\Http\Controllers\AlianzaController@gestionUsuarios');
    }
    public function eliminarPeticion2(request $info){
        
        $query ="DELETE FROM peticiones_aldea WHERE id = ".$info->id;
        $t=DB::select($query);
        return redirect()->action('App\Http\Controllers\AlianzaController@index');
    }
    public function aceptarpeticion(request $info){
        //se obtienen los datos de la peticion
        $query ="SELECT * FROM peticiones_aldea WHERE id= ".$info->id;
        $t=DB::select($query);
        foreach($t as $s)
        {
            $idAlianza = $s->id_alianza;
            $idUsuario = $s->id_usuario;
        }
        //se updatea el usuario
        $query ="UPDATE users SET alianza = ".$idAlianza. " where id = ".$idUsuario;
        $t=DB::select($query);
        //se elimina la invitacion
        $query ="DELETE FROM peticiones_aldea WHERE id = ".$info->id;
        $t=DB::select($query);
        $user= auth()->user(); 
        $user->givePermissionTo('soldado_raso');
        return redirect()->action('App\Http\Controllers\AlianzaController@index');
    }
    public function dejarAli(request $info){
        //desde datos alianza (el usuario)
        $idUsu =auth()->id();
        $query ="UPDATE users SET alianza = null where id = ".$idUsu;
        $t=DB::select($query);
        //quitar los permisos que tien
        $query = "SELECT valor FROM parametrizaciones WHERE  lista = 'PermisosAlianzas' and nombre<>'TITULO';";
        $todos_permisos=DB::select($query);

        $userpermiso = User::whereId($idUsu)->first(); 
        foreach($todos_permisos as $permiso){
            if($userpermiso->hasPermissionTo($permiso->valor)==1){
                $userpermiso->revokePermissionTo($permiso->valor);
            }
           }
        
        return redirect()->action('App\Http\Controllers\AlianzaController@index');
    }
    public function dejarali2(request $info){
        //DEsde gestion cuentas, el lider de la alianza
        $query ="UPDATE users SET alianza = null where id = ".$info->idUsu;
        $t=DB::select($query);
        //quitar los permisos que tien
        $query = "SELECT valor FROM parametrizaciones WHERE  lista = 'PermisosAlianzas' and valor <>'TITULO';";
        $todos_permisos=DB::select($query);

        $userpermiso = User::whereId($info->idUsu)->first(); 
        foreach($todos_permisos as $permiso){
            if($userpermiso->hasPermissionTo($permiso->valor)==1){
                $userpermiso->revokePermissionTo($permiso->valor);
            }
           }
        
        return redirect()->action('App\Http\Controllers\AlianzaController@gestionUsuarios');
    }
    public function aldeasimportantes(){
            //SAco usuario 
            $idUsu =auth()->id();
            //desde usuario saco aldeas interes
            $query =   "SELECT DISTINCT aldea_inac.coord_x, aldea_inac.coord_y, aldea_inac.NombreAldea, aldeas_interes.tipo, aldeas_interes.id, cuenta_inac.NombreCuenta as jugador, alianza_inac.NombreAlianza as alianza FROM aldeas_interes, aldea_inac, users, servidor ,cuenta_inac, alianza_inac WHERE aldea_inac.idAldea = aldeas_interes.id_aldea and aldea_inac.id_server = aldeas_interes.id_servidor  and aldeas_interes.id_alianza =  users.alianza and users.id = ".$idUsu." and servidor.id = aldeas_interes.id_servidor and aldea_inac.created_at = servidor.fch_mod and cuenta_inac.IdCuenta = aldea_inac.IdCuenta and cuenta_inac.IdServer = servidor.id and alianza_inac.IdAlianza = cuenta_inac.IdAlianza and alianza_inac.id_Server = servidor.id" ;
            $aldeas=DB::select($query);
        return  view('importantes')->with('aldeas',$aldeas);
    }
    public function aldeasimportantesadd(request $info){
        //SAco usuario 
        $idUsu =auth()->id();
        //desde usuario saco aldeas interes
        $query =   "SELECT a.idAldea as id,a.id_server, users.alianza FROM aldea_inac a, servidor, users WHERE a.coord_x = ".$info->coord_x."  AND a.coord_y = ".$info->coord_y." and users.id = ".$idUsu." AND a.id_server = servidor.id and servidor.id = users.servidor and servidor.fch_mod = a.created_at;";
        $aldeas=DB::select($query);
        foreach($aldeas as $s)
        {
            $id_aldea = $s->id;
            $servidor = $s->id_server;
            $alianza = $s->alianza;
        }
        $query =   "INSERT INTO aldeas_interes (id_alianza, id_aldea, id_servidor, tipo) VALUES ( $alianza, $id_aldea, $servidor, '".$info->tipo."')";
        $aldeas=DB::select($query);

        return redirect()->action('App\Http\Controllers\AlianzaController@aldeasimportantes');    
    }
    public function aldeasimportantessub(request $info){
        //SAco usuario 
        $idUsu =auth()->id();
        //desde usuario saco aldeas interes
        $query =   "DELETE FROM aldeas_interes WHERE id =  ".$info->idImportante;
        $aldeas=DB::select($query);

        return redirect()->action('App\Http\Controllers\AlianzaController@aldeasimportantes');   
    }
    public function round(){
        //SAco usuario 
        $idUsu =auth()->id();

        //necesito off de la alianza (nombre de aldea y jugador, con coordenadas)
        $query = "select aldea.id,  users.raza, users.login as cuenta, aldea.nombre,aldea.tipo, aldea.coord_x, aldea.coord_y, aldea.tropa_1,aldea.tropa_2, aldea.tropa_3,aldea.tropa_4,aldea.tropa_5,aldea.tropa_6,aldea.tropa_7,aldea.tropa_8,aldea.tropa_9,aldea.tropa_10,aldea.tropa_11 from users, aldea where aldea.id_cuenta = users.id and  alianza = (select alianza from users where id = ".$idUsu.") and aldea.tipo in (select valor from parametrizaciones where lista = 'Aldeas_off')order by raza asc;";
        $aldeas_off=DB::select($query);
        //aldeas importantes
        $query =   "SELECT DISTINCT aldea_inac.coord_x, aldea_inac.coord_y, aldea_inac.NombreAldea, aldeas_interes.tipo, aldeas_interes.id, cuenta_inac.NombreCuenta as jugador, alianza_inac.NombreAlianza as alianza FROM aldeas_interes, aldea_inac, users, servidor ,cuenta_inac, alianza_inac WHERE aldea_inac.idAldea = aldeas_interes.id_aldea and aldea_inac.id_server = aldeas_interes.id_servidor  and aldeas_interes.id_alianza =  users.alianza and users.id = ".$idUsu." and servidor.id = aldeas_interes.id_servidor and aldea_inac.created_at = servidor.fch_mod and cuenta_inac.IdCuenta = aldea_inac.IdCuenta and cuenta_inac.IdServer = servidor.id and alianza_inac.IdAlianza = cuenta_inac.IdAlianza and alianza_inac.id_Server = servidor.id order by 6" ;
        $aldeas_interes=DB::select($query);
        //aldeas importantes
        return  view('round')->with('aldeas_interes',$aldeas_interes)->with('aldeas_off',$aldeas_off);
    }
    public function Anadirround(request $info){
        //SAco usuario 
        $query =   "SELECT alianza, users.servidor as id_server FROM aldea, users  where users.id = aldea.id_cuenta and  aldea.id =".$info->aldeas_off;
        $aldeas=DB::select($query);
        foreach($aldeas as $s)
        {
            $servidor = $s->id_server;
            $alianza = $s->alianza;
        }

        $query =   "INSERT INTO `round` (id_alianza, id_lanza, id_objetivo,id_server, hora_llegada) VALUES ( ".$alianza.", ".$info->aldeas_off.", ".$info->aldeas_interes.", ".$servidor.", '".$info->dia." ".$info->hora."');";
        $aldeas_interes=DB::select($query);
        return redirect()->action('App\Http\Controllers\AlianzaController@round');   
    }
    public function Eliminarround(request $info){
        //SAco usuario 
        $idUsu =auth()->id();

        return redirect()->action('App\Http\Controllers\AlianzaController@round');   

    }
    public function plan_Deff(request $info){
        //SAco usuario 
        $idUsu =auth()->id();
        $query = "SELECT alianza FROM users WHERE ID = ".$idUsu;
        $t=DB::select($query);
        foreach($t as $s)
        {
            $alianza = $s->alianza;
          
        }
        $date = Carbon::now();
        $query = "SELECT ataque.id_ataque as idAtaque, heroe.fecha_cambio as cambio_heroe, cuenta_inac.NombreCuenta as cuenta_ata,aldea_inac.NombreAldea as nombre_ata, aldea_inac.coord_x as x_ata, aldea_inac.coord_y y_ata, alianza_inac.NombreAlianza,ataque.salida, ataque.llegada,ataque.velocidad, ataque.pt, users.login as cuenta_deff, aldea.tipo, aldea.nombre as nombre_deff, aldea.coord_x as x_deff, aldea.coord_y as x_deff , ataque.intercalada, ataque.vagones FROM ataque, aldea,users , alianza_inac ,cuenta_inac , aldea_inac, servidor, heroe WHERE  heroe.id_cuenta = cuenta_inac.IdCuenta  and heroe.id_alianza = ataque.id_alianza  and cuenta_inac.IdCuenta = aldea_inac.IdCuenta and aldea_inac.id_server = users.servidor and aldea_inac.created_at = servidor.fch_mod and alianza_inac.id_Server = servidor.id and cuenta_inac.IdAlianza = alianza_inac.IdAlianza and servidor.id = users.servidor and ataque.id_aldea_at = aldea_inac.idAldea and cuenta_inac.IdServer = servidor.id and users.id = aldea.id_cuenta and ataque.id_alianza =".$alianza." and aldea.id = ataque.id_aldea_deff and llegada >= '".$date->toDateTimeString()."'";
        $t=DB::select($query); 
        //echo $query;

        return  view('plandeff')->with('aldeas',$t);
    }
    public function deffdisponible(request $info){
        $idUsu =auth()->id();

        $query = "select users.raza, users.login as cuenta, aldea.nombre,aldea.tipo, aldea.coord_x, aldea.coord_y, aldea.tropa_1,aldea.tropa_2, aldea.tropa_3,aldea.tropa_4,aldea.tropa_5,aldea.tropa_6,aldea.tropa_7,aldea.tropa_8,aldea.tropa_9,aldea.tropa_10,aldea.tropa_11 from users, aldea where aldea.id_cuenta = users.id and  alianza = (select alianza from users where id = ".$idUsu.") and aldea.tipo in (select valor from parametrizaciones where lista = 'Aldeas_deff') and users.raza = 1 order by raza asc;";
        $aldeas_romanas=DB::select($query);

        $query = "select nombre_tropa from tropas  where raza = 1 order by raza ,orden";
        $tropas_romanas=DB::select($query);
        //////////////////////////////
        $query = "select users.raza, users.login as cuenta, aldea.nombre,aldea.tipo, aldea.coord_x, aldea.coord_y, aldea.tropa_1,aldea.tropa_2, aldea.tropa_3,aldea.tropa_4,aldea.tropa_5,aldea.tropa_6,aldea.tropa_7,aldea.tropa_8,aldea.tropa_9,aldea.tropa_10,aldea.tropa_11 from users, aldea where aldea.id_cuenta = users.id and  alianza = (select alianza from users where id = ".$idUsu.") and aldea.tipo in (select valor from parametrizaciones where lista = 'Aldeas_deff') and users.raza = 3 order by raza asc;";
        $aldeas_galas=DB::select($query);

        $query = "select nombre_tropa from tropas  where raza = 3 order by raza ,orden";
        $tropas_galas=DB::select($query);

        //////////////////////////////
        $query = "select users.raza, users.login as cuenta, aldea.nombre,aldea.tipo, aldea.coord_x, aldea.coord_y, aldea.tropa_1,aldea.tropa_2, aldea.tropa_3,aldea.tropa_4,aldea.tropa_5,aldea.tropa_6,aldea.tropa_7,aldea.tropa_8,aldea.tropa_9,aldea.tropa_10,aldea.tropa_11 from users, aldea where aldea.id_cuenta = users.id and  alianza = (select alianza from users where id = ".$idUsu.") and aldea.tipo in (select valor from parametrizaciones where lista = 'Aldeas_deff') and users.raza = 2 order by raza asc;";
        $aldeas_germanas=DB::select($query);

        $query = "select nombre_tropa from tropas  where raza = 2 order by raza ,orden";
        $tropas_germanas=DB::select($query);

        //print_r($encole);
        return  view('aldeas_deff')->with('aldeas_romanas',$aldeas_romanas)->with('tropas_romanas',$tropas_romanas)->with('aldeas_germanas',$aldeas_germanas)->with('tropas_germanas',$tropas_germanas)->with('aldeas_galas',$aldeas_galas)->with('tropas_galas',$tropas_galas);
    }
    public function generarPush(request $info){
        
        $idUsu =auth()->id();
        //1.- conteo cuantos son en la alianza
        $idAlianza  =0;
        $query = "select alianza from users where id = ".$idUsu;
        $t=DB::select($query);
        
        foreach($t as $s)
        {
            $idAlianza = $s->alianza;
          
        }

        $numAlianza=0;
        $query = "select count(*) as count from users u where u.alianza =".$idAlianza;
        $t=DB::select($query);
        
        foreach($t as $s)
        {
            $numAlianza = $s->count;
          
        }
       
        $cantidadIndi = round($info->cantidad/$numAlianza,0);
        //2.- creo registro en push_alianzas
        $query = "INSERT INTO push_alianzas(id_alianza,coord_x_recibe, coord_y_recibe, cantidad_total) VALUES (".$idAlianza.",".$info->coor_x.",".$info->coor_y.",".$info->cantidad." )";
        $t=DB::select($query);

        $idPush=0;
        $query = "select max(id)  as count from push_alianzas u where u.id_alianza =".$idAlianza;
        $t=DB::select($query);
        foreach($t as $s)
        {
            $idPush = $s->count;
          
        }
        // 2.1 push
        $query = "select *  from users u where u.alianza =".$idAlianza;
        $t=DB::select($query);
        
        foreach($t as $s)
        {
            $query = "INSERT INTO push(id_push_alianza,usuario_envio, estado, cantidad) VALUES (".$idPush.",".$s->id.",1,".$cantidadIndi.")";
            $t=DB::select($query);
            foreach($t as $s)
            {
                $numAlianza = $s->alianza;
              
            }
            $mensaje = "Nuevo push necesario, cantidad:".$cantidadIndi." a las coordenadas: ".$info->coor_x."/".$info->coor_y." Por favor, cuando envíe no se olvide de notificarlo en la web(alianza->push)";
        
            $link='travianstat.es/login';
            $query ="SELECT YEAR(NOW()) as ano,MONTH (NOW())as mes ,DAY(NOW()) as dia,DATE_FORMAT(NOW( ), '%H' ) as hora,DATE_FORMAT(NOW( ), '%i' ) +2 as minuto FROM dual;";
            $q=DB::select($query);
            foreach($q as $w)
            {
                $minuto = $w->minuto;
                $hora = $w->hora;
                $dia = $w->dia;
                $mes = $w->mes;
                $ano = $w->ano;
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
            
            
            $query = "INSERT INTO notificaciones_telegram( id_usuario, texto, link, `ano`, `mes`, `dia`, `hora`, `minuto`, `enviado`) VALUES ('".$s->id."','".$mensaje."','".$link."','".$ano."','".$mes."','".$dia."','".$hora."','".$minuto."','0')";
            $aldea=DB::select($query);
        }
         
 
        
        

        return redirect()->action('App\Http\Controllers\AlianzaController@gestionUsuarios');
    }
    public function pushPendiente(){
        //SAco usuario 
        $idUsu=auth()->id();
        $query = "SELECT s.ruta as rutaServer,  i.NombreAldea,pa.coord_x_recibe, pa.coord_y_recibe, p.cantidad , p.id FROM `push` p, push_alianzas pa, aldea_inac i, servidor s, users u  WHERE  p.id_push_alianza = pa.id  and p.estado >0 and pa.coord_x_recibe = i.coord_x and pa.coord_y_recibe = i.coord_y and u.servidor = i.id_server and i.created_at = s.fch_mod and  p.usuario_envio = u.id  and u.id = ".$idUsu;
        $tropas_germanas=DB::select($query);

       
        return  view('alianza.pushpendiente')->with('push',$tropas_germanas)->with('mensaje',''); 
    }
    
    public function cierrepush(request $info){
        //SAco usuario 
        $idUsu=auth()->id();
       
        $query = "update push set estado = 0 where id= ".$info->identificador;
        $tropas_germanas=DB::select($query);

        //si es el último pone estado a 0 como acabado en push
       
        return redirect()->action('App\Http\Controllers\AlianzaController@pushPendiente');   

 
    }
    public function gestionpush(){
        //Saco usuario 
        $idUsu=auth()->id();
        $idAlianza  =0;
        $query = "select alianza from users where id = ".$idUsu;
        $t=DB::select($query);
        
        foreach($t as $s)
        {
            $idAlianza = $s->alianza;
          
        }

        $numAlianza=0;
        $query = "select count(*) as count from users u where u.alianza =".$idAlianza;
        $t=DB::select($query);
        
        foreach($t as $s)
        {
            $numAlianza = $s->count;
          
        }
        $query = "SELECT distinct s.ruta as rutaServer, i.NombreAldea,pa.coord_x_recibe, pa.coord_y_recibe, p.cantidad , pa.id, (select count(*) from push p2 where p2.id_push_alianza = pa.id and p2.estado >0 ) as pendientes FROM `push` p, push_alianzas pa, aldea_inac i, servidor s, users u WHERE p.id_push_alianza = pa.id and p.estado >0 and pa.coord_x_recibe = i.coord_x and pa.coord_y_recibe = i.coord_y and u.servidor = i.id_server and i.created_at = s.fch_mod and pa.id_alianza = u.alianza and u.id =  ".$idUsu;
        $tropas_germanas=DB::select($query);

       
        return  view('alianza.gestionpush')->with('push',$tropas_germanas)->with('mensaje','')->with('numAlianza',$numAlianza); 
    }
    public function informacionPush(){
        //Saco usuario 
        $idUsu=auth()->id();
        $query = "SELECT distinct s.ruta as rutaServer, i.NombreAldea,pa.coord_x_recibe, pa.coord_y_recibe, p.cantidad , pa.id, (select count(*) from push p2 where p2.id_push_alianza = pa.id and p2.estado >0 ) as pendientes FROM `push` p, push_alianzas pa, aldea_inac i, servidor s, users u WHERE p.id_push_alianza = pa.id and p.estado >0 and pa.coord_x_recibe = i.coord_x and pa.coord_y_recibe = i.coord_y and u.servidor = i.id_server and i.created_at = s.fch_mod and p.usuario_envio = u.id and u.id = ".$idUsu;
        $tropas_germanas=DB::select($query);

       
        return  view('alianza.gestionpush')->with('push',$tropas_germanas)->with('mensaje',''); 
    }
    public function cancelarpush(){
        //Saco usuario 
        $idUsu=auth()->id();
        $query = "SELECT distinct s.ruta as rutaServer, i.NombreAldea,pa.coord_x_recibe, pa.coord_y_recibe, p.cantidad , pa.id, (select count(*) from push p2 where p2.id_push_alianza = pa.id and p2.estado >0 ) as pendientes FROM `push` p, push_alianzas pa, aldea_inac i, servidor s, users u WHERE p.id_push_alianza = pa.id and p.estado >0 and pa.coord_x_recibe = i.coord_x and pa.coord_y_recibe = i.coord_y and u.servidor = i.id_server and i.created_at = s.fch_mod and pa.id_alianza = u.alianza and u.id =  ".$idUsu;
        $tropas_germanas=DB::select($query);

       
        return  view('alianza.gestionpush')->with('push',$tropas_germanas)->with('mensaje',''); 
    }
    
    
}