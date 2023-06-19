<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user =   User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->givePermissionTo('Usuario_travian');

        /////Envio mensaje nuevo usuario
        $mensaje = "nuevo usuario creado nombre:".$data['name']." email:".$data['email'];
        $link='travianstat.es/login';
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
            
            
        $query = "INSERT INTO notificaciones_telegram( id_usuario, texto, link, `ano`, `mes`, `dia`, `hora`, `minuto`, `enviado`) VALUES ('7','".$mensaje."','".$link."','".$ano."','".$mes."','".$dia."','".$hora."','".$minuto."','0')";
        $aldea=DB::select($query);
        
        return $user;
    }
}
