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
class Controller_admin_permisos extends Controller
{
     public function index()
    {
        $usuarios = [];
        $users = DB::table('users')->get();
        $todos_permisos = Permission::all()->pluck('name');
         foreach($users as $usuario)
        {
            $usu = User::whereId($usuario->id)->first(); 
            
            array_push($usuarios , $usu);
        }
         return  view('permisos.index')->with('usuarios',$usuarios)->with('permisos',$todos_permisos);
    }
    public function crearpermiso(request $info)
    {
        $permission = Permission::create(['name' => $info->nombrePermiso]);
        return redirect()->action('App\Http\Controllers\Controller_admin_permisos@index');
    }
    public function AnadirPermiso(request $info)
    {
        $userpermiso = User::whereId($info->idUsu)->first(); 
        $userpermiso->givePermissionTo($info->permiso);
        return redirect()->action('App\Http\Controllers\Controller_admin_permisos@index');
    }
    public function EliminarPermisoUsuario(request $info)
    {
        $userpermiso = User::whereId($info->idUsu)->first(); 
        $userpermiso->revokePermissionTo($info->permiso);
        return redirect()->action('App\Http\Controllers\Controller_admin_permisos@index');
    }
    public function EliminarUsuario(request $info)
    {
        $query = "DELETE FROM users WHERE id = ".$info->idUsu."";
        $tropas=DB::select($query);
        return redirect()->action('App\Http\Controllers\Controller_admin_permisos@index');
    }
}
