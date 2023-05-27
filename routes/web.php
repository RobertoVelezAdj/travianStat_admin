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
    Route::get('/dashboard', [App\Http\Controllers\Controller_aldeas::class, 'index'])->name('dashboard');
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
Route::put('/Apuestas/Crear', [App\Http\Controllers\Controller_admin_apuestas::class, 'Crear'])->middleware('can:Apuestas')->name('Apuestas.Crear'); 
Route::put('/Apuestas/FinalizarApuesta', [App\Http\Controllers\Controller_admin_apuestas::class, 'FinalizarApuesta'])->middleware('can:Apuestas')->name('Apuestas.FinalizarApuesta'); 
Route::get('/Apuestas/Cerradas', [App\Http\Controllers\Controller_admin_apuestas::class, 'cerradas'])->middleware('can:Apuestas')->name('Apuestas.cerradas'); 
Route::get('/Apuestas/Historico', [App\Http\Controllers\Controller_admin_apuestas::class, 'historico'])->middleware('can:Apuestas')->name('Apuesta.historicos'); 
//Admin servidores
Route::get('/AdminServidores', [App\Http\Controllers\Controller_admin_servidor::class, 'index'])->middleware('can:Administrador')->name('AdminServidores'); 
Route::put('/AdminServidores/Crear', [App\Http\Controllers\Controller_admin_servidor::class, 'crear'])->middleware('can:Administrador')->name('AdminServidores.Crear'); 
Route::put('/AdminServidores/borrar', [App\Http\Controllers\Controller_admin_servidor::class, 'borrar'])->middleware('can:Administrador')->name('AdminServidores.borrar'); 


//Aldeas
Route::get('/Aldeas/informacion', [App\Http\Controllers\Controller_aldeas::class, 'index'])->name('aldeas.informacion'); 
Route::put('/Aldeas/crear', [App\Http\Controllers\Controller_aldeas::class, 'Crear'])->name('aldeas.crear'); 
Route::put('/Aldeas/editar', [App\Http\Controllers\Controller_aldeas::class, 'editar'])->name('aldeas.editar'); 
Route::put('/Aldeas/borrar', [App\Http\Controllers\Controller_aldeas::class, 'borrar'])->name('aldeas.borrar'); 



Route::get('/Aldeas/edificios', [App\Http\Controllers\Controller_aldeas::class, 'edificios'])->name('aldeas.edificios'); 
Route::put('/Aldeas/Editaredificios', [App\Http\Controllers\Controller_aldeas::class, 'editarEdificios'])->name('aldeas.editarEdificios'); 

Route::get('/Aldeas/tareas', [App\Http\Controllers\Controller_aldeas::class, 'index'])->name('aldeas.tareas'); 
//Mi cuenta
Route::get('/MiCuenta/Informacion', [App\Http\Controllers\Controller_micuenta::class, 'index'])->name('MiCuenta.Informacion'); 
Route::put('/MiCuenta/Modificar', [App\Http\Controllers\Controller_micuenta::class, 'modificar'])->name('aldeas.crear'); 
//historificacion
Route::get('/historico', [App\Http\Controllers\Controller_admin_apuestas::class, 'historificacion'])->name('historico'); 
