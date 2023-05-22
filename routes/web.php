<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




//Admin usuarios
Route::get('/AdminPermisos', [App\Http\Controllers\Controller_admin_permisos::class, 'index'])->middleware('can:Administrador')->name('AdminPermisos'); 
Route::put('/AdminPermisos/Crear', [App\Http\Controllers\Controller_admin_permisos::class, 'crearpermiso'])->middleware('can:Administrador')->name('AdminPermisos.Crear'); 
Route::put('/AdminPermisos/Añadir', [App\Http\Controllers\Controller_admin_permisos::class, 'AnadirPermiso'])->middleware('can:Administrador')->name('AdminPermisos.AnadirPermiso');
Route::put('/AdminPermisos/EliminarPermisoUsuario', [App\Http\Controllers\Controller_admin_permisos::class, 'EliminarPermisoUsuario'])->middleware('can:Administrador')->name('AdminPermisos.EliminarPermisoUsuario');
Route::put('/AdminPermisos/EliminarPermisoTabla', [App\Http\Controllers\Controller_admin_permisos::class, 'EliminarPermisoTabla'])->middleware('can:Administrador')->name('AdminPermisos.Eliminar');

//Admin parametrizaciones
Route::get('/AdminParametrizaciones', [App\Http\Controllers\Controller_admin_parametrizaciones::class, 'index'])->middleware('can:Administrador')->name('Parametrizaciones'); 
Route::put('/AdminParametrizaciones/Crear', [App\Http\Controllers\Controller_admin_parametrizaciones::class, 'crearLista'])->middleware('can:Administrador')->name('Parametrizaciones.crearLista'); 
Route::put('/AdminParametrizaciones/Eliminar', [App\Http\Controllers\Controller_admin_parametrizaciones::class, 'EliminarLista'])->middleware('can:Administrador')->name('Parametrizaciones.crearLista'); 

Route::get('/AdminParametrizaciones/MostrarRegistro', [App\Http\Controllers\Controller_admin_parametrizaciones::class, 'MostrarLista'])->middleware('can:Administrador')->name('Parametrizaciones.MostrarLista'); 
Route::put('/AdminParametrizaciones/CrearRegistro', [App\Http\Controllers\Controller_admin_parametrizaciones::class, 'crearRegistro'])->middleware('can:Administrador')->name('Parametrizaciones.crearRegistro'); 
Route::put('/AdminParametrizaciones/EliminarRegistro', [App\Http\Controllers\Controller_admin_parametrizaciones::class, 'EliminarRegistro'])->middleware('can:Administrador')->name('Parametrizaciones.EliminarRegistro'); 


//Admin apuestas
Route::get('/Apuestas/Estado', [App\Http\Controllers\Controller_admin_apuestas::class, 'Abiertas'])->middleware('can:Apuestas')->name('Apuestas'); 
Route::put('/Apuestas/Crear', [App\Http\Controllers\Controller_admin_apuestas::class, 'Crear'])->middleware('can:Administrador')->name('Apuestas.Crear'); 
Route::put('/Apuestas/FinalizarApuesta', [App\Http\Controllers\Controller_admin_apuestas::class, 'FinalizarApuesta'])->middleware('can:Administrador')->name('Apuestas.FinalizarApuesta'); 

Route::get('/Apuestas/Cerradas', [App\Http\Controllers\Controller_admin_apuestas::class, 'cerradas'])->middleware('can:Apuestas')->name('Apuestas.cerradas'); 
Route::get('/Apuestas/Historico', [App\Http\Controllers\Controller_admin_apuestas::class, 'historico'])->middleware('can:Apuestas')->name('Apuesta.historicos'); 

//Aldeas
Route::get('/Aldeas/informacion', [App\Http\Controllers\Controller_aldeas::class, 'index'])->name('aldeas.informacion'); 
Route::put('/Aldeas/crear', [App\Http\Controllers\Controller_aldeas::class, 'Crear'])->name('aldeas.crear'); 

Route::get('/Aldeas/edificios', [App\Http\Controllers\Controller_aldeas::class, 'index'])->name('aldeas.edificios'); 

Route::get('/Aldeas/tareas', [App\Http\Controllers\Controller_aldeas::class, 'index'])->name('aldeas.tareas'); 


//historificacion
Route::get('/historico', [App\Http\Controllers\Controller_admin_apuestas::class, 'historificacion'])->name('historico'); 
