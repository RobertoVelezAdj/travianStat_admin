<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



use App\Models\mensaje;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendNotification;

class avisosController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function avisos()
    {
        $query ='SELECT n.id, c.id_telegram as id_chat, n.texto, link FROM  notificaciones_telegram n , users c WHERE ano = YEAR(NOW()) and mes = MONTH (NOW()) and dia = DAY(NOW()) and hora = DATE_FORMAT(NOW( ), "%H" ) and minuto = DATE_FORMAT(NOW( ), "%i" ) and n.id_usuario = c.id and enviado = 0';
        $sa=DB::select($query);
        foreach ($sa as $a){
            $id_notificacion =  $a->id;
            $id_chat = $a->id_chat;
            $texto = $a->texto;
            $link = $a->link;
             $mensaje = new mensaje ([
                'telegramid' => $id_chat,
                'texto' =>$texto,
                'link' => $link ,
                'parse_mode' =>  'HTML'
            ]);
    
            $query = "UPDATE notificaciones_telegram SET enviado = 1 WHERE id = ".$id_notificacion;
             $mensaje->notify(new SendNotification());
             
            $sa=DB::select($query);
        }
       
    }
    
}
