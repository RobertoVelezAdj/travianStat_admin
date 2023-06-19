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
    Route::get('/Aldeas/informacion', [App\Http\Controllers\Controller_aldeas::class, 'redirec'])->name('dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\Controller_aldeas::class, 'redirec'])->name('home');




//Admin usuarios
Route::get('/AdminPermisos', [App\Http\Controllers\Controller_admin_permisos::class, 'index'])->middleware('can:Administrador')->name('AdminPermisos'); 
Route::put('/AdminPermisos/Crear', [App\Http\Controllers\Controller_admin_permisos::class, 'crearpermiso'])->middleware('can:Administrador')->name('AdminPermisos.Crear'); 
Route::put('/AdminPermisos/Añadir', [App\Http\Controllers\Controller_admin_permisos::class, 'AnadirPermiso'])->middleware('can:Administrador')->name('AdminPermisos.AnadirPermiso');
Route::put('/AdminPermisos/EliminarPermisoUsuario', [App\Http\Controllers\Controller_admin_permisos::class, 'EliminarPermisoUsuario'])->middleware('can:Administrador')->name('AdminPermisos.EliminarPermisoUsuario');
Route::put('/AdminPermisos/EliminarUsuario', [App\Http\Controllers\Controller_admin_permisos::class, 'EliminarUsuario'])->middleware('can:Administrador')->name('AdminPermisos.Eliminar');
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
Route::get('/Aldeas/informacion', [App\Http\Controllers\Controller_aldeas::class, 'index'])->middleware('auth')->name('aldeas.informacion'); 
Route::put('/Aldeas/crear', [App\Http\Controllers\Controller_aldeas::class, 'Crear'])->middleware('can:Usuario_travian')->name('aldeas.crear'); 
Route::put('/Aldeas/editar', [App\Http\Controllers\Controller_aldeas::class, 'editar'])->middleware('can:Usuario_travian')->name('aldeas.editar'); 
Route::put('/Aldeas/borrar', [App\Http\Controllers\Controller_aldeas::class, 'borrar'])->middleware('can:Usuario_travian')->name('aldeas.borrar'); 
Route::put('/Aldeas/actualizarprod', [App\Http\Controllers\Controller_aldeas::class, 'actualizarprod'])->middleware('can:Usuario_travian')->name('aldeas.actualizarprod'); 
Route::put('/Aldeas/actualizarpc', [App\Http\Controllers\Controller_aldeas::class, 'actualizarpc'])->middleware('can:Usuario_travian')->name('aldeas.actualizarpc'); 


Route::get('/Aldeas/tropas', [App\Http\Controllers\Controller_aldeas::class, 'mistropas'])->middleware('can:Usuario_travian')->name('aldeas.mistropas'); 
Route::put('/Aldeas/actualizar', [App\Http\Controllers\Controller_aldeas::class, 'actualizar'])->middleware('can:Usuario_travian')->name('aldeas.actualizar'); 

Route::get('/Aldeas/tareas', [App\Http\Controllers\Controller_aldeas::class, 'tareas'])->middleware('can:Usuario_travian')->name('aldeas.tareas'); 
Route::put('/Aldeas/nuevaTarea', [App\Http\Controllers\Controller_aldeas::class, 'nuevaTarea'])->middleware('can:Usuario_travian')->name('aldeas.nuevaTarea'); 
Route::put('/Aldeas/editartarea', [App\Http\Controllers\Controller_aldeas::class, 'editartarea'])->middleware('can:Usuario_travian')->name('aldeas.editartarea'); 
Route::put('/Aldeas/completarTarea', [App\Http\Controllers\Controller_aldeas::class, 'completarTarea'])->middleware('can:Usuario_travian')->name('aldeas.completarTarea'); 

Route::get('/Aldeas/encoles', [App\Http\Controllers\Controller_aldeas::class, 'encole'])->middleware('can:Usuario_travian')->name('aldeas.encole'); 
Route::put('/Aldeas/nuevoencole', [App\Http\Controllers\Controller_aldeas::class, 'nuevoencole'])->middleware('can:Usuario_travian')->name('aldeas.nuevoencole'); 
Route::put('/Aldeas/eliminarencole', [App\Http\Controllers\Controller_aldeas::class, 'eliminarencole'])->middleware('can:Usuario_travian')->name('aldeas.eliminarencole'); 


Route::get('/Aldeas/edificios', [App\Http\Controllers\Controller_aldeas::class, 'edificios'])->middleware('can:Usuario_travian')->name('aldeas.edificios'); 
Route::put('/Aldeas/Editaredificios', [App\Http\Controllers\Controller_aldeas::class, 'editarEdificios'])->middleware('can:Usuario_travian')->name('aldeas.editarEdificios'); 
//calculos
Route::get('/Calculos/rutas', [App\Http\Controllers\Controller_calculos::class, 'rutas'])->middleware('can:Usuario_travian')->name('aldeas.encole'); 
Route::put('/Calculos/nuevaruta', [App\Http\Controllers\Controller_calculos::class, 'nuevaruta'])->middleware('can:Usuario_travian')->name('aldeas.encole'); 
Route::put('/Calculos/editarruta', [App\Http\Controllers\Controller_calculos::class, 'editarruta'])->middleware('can:Usuario_travian')->name('aldeas.editarruta'); 
Route::put('/Calculos/borrarruta', [App\Http\Controllers\Controller_calculos::class, 'borrarruta'])->middleware('can:Usuario_travian')->name('aldeas.borrarruta'); 

Route::get('/Calculos/npc', [App\Http\Controllers\Controller_calculos::class, 'npc'])->middleware('can:Usuario_travian')->name('aldeas.npc'); 

//vacas

Route::get('/Vacas/busqueda', [App\Http\Controllers\Controller_vacas::class, 'inicio'])->middleware('can:Usuario_travian')->name('aldeas.inicio');
Route::put('/Vacas/actualizar_pago', [App\Http\Controllers\Controller_vacas::class, 'actualizar_pago'])->middleware('can:Usuario_travian')->name('aldeas.actualizar_pago'); 
Route::put('/vacas/insertarVacas', [App\Http\Controllers\Controller_vacas::class, 'insertarVacas'])->middleware('can:Usuario_travian')->name('insertarVacas');
Route::put('/Vacas/calculovacas', [App\Http\Controllers\Controller_vacas::class, 'calculovacas'])->middleware('can:Usuario_travian')->name('calculovacas');

Route::get('/Vacas/mislistas', [App\Http\Controllers\Controller_vacas::class, 'listaVacas'])->middleware('can:Usuario_travian')->name('aldeas.listaVacas');
Route::put('/Vacas/eliminarlista', [App\Http\Controllers\Controller_vacas::class, 'eliminarVaca'])->middleware('can:Usuario_travian')->name('aldeas.eliminarVaca');
Route::put('/Vacas/actualizar_pago2', [App\Http\Controllers\Controller_vacas::class, 'actualizar_pago2'])->middleware('can:Usuario_travian')->name('aldeas.actualizar_pago2'); 

 //Mi cuenta
Route::get('/MiCuenta/Informacion', [App\Http\Controllers\Controller_micuenta::class, 'index'])->middleware('can:Usuario_travian')->name('MiCuenta.Informacion'); 
Route::put('/MiCuenta/Modificar', [App\Http\Controllers\Controller_micuenta::class, 'modificar'])->middleware('can:Usuario_travian')->name('aldeas.crear'); 


//historificacion
Route::get('/historico', [App\Http\Controllers\Controller_admin_apuestas::class, 'historificacion'])->name('historico'); 



//AVISOS
Route::get('/avisos', [App\Http\Controllers\avisosController::class, 'avisos'])->name('avisos');
Route::get('/Aldeas/actualizarNombres', [App\Http\Controllers\Controller_aldeas::class, 'actualizarNombres'])->name('aldeas.actualizarNombres'); 



//alianzas

Route::get('/datosalianza', [App\Http\Controllers\AlianzaController::class, 'index'])->middleware('can:Usuario_travian')->name('adminAlianza');
Route::put('/datosalianza/crearAlianza', [App\Http\Controllers\AlianzaController::class, 'crearAli'])->middleware('can:Usuario_travian')->name('crearAli');
Route::put('/datosalianza/editarAlianza', [App\Http\Controllers\AlianzaController::class, 'editarAli'])->middleware('can:lider_alianza')->name('editarAli');
 

Route::get('/datosalianza/gestioncuentas', [App\Http\Controllers\AlianzaController::class, 'gestionUsuarios'])->middleware('can:lider_alianza')->name('gestionUsuarios');
Route::put('/datosalianza/anadirUsu', [App\Http\Controllers\AlianzaController::class, 'anadirUsu'])->middleware('can:lider_alianza')->name('anadirUsu');
Route::put('/datosalianza/AnadirPermiso', [App\Http\Controllers\AlianzaController::class, 'AnadirPermiso'])->middleware('can:lider_alianza')->name('datosalianza.AnadirPermiso');
Route::put('/datosalianza/EliminarPermiso', [App\Http\Controllers\AlianzaController::class, 'EliminarPermiso'])->middleware('can:lider_alianza')->name('datosalianza.EliminarPermiso');
Route::put('/datosalianza/eliminarPeticion', [App\Http\Controllers\AlianzaController::class, 'eliminarPeticion'])->middleware('can:lider_alianza')->name('datosalianza.eliminarPeticion');
Route::put('/datosalianza/aceptarPeticion', [App\Http\Controllers\AlianzaController::class, 'aceptarpeticion'])->middleware('can:Usuario_travian')->name('datosalianza.eliminarPeticion');
Route::put('/datosalianza/saliralianza', [App\Http\Controllers\AlianzaController::class, 'salirAianza'])->middleware('can:Usuario_travian')->name('datosalianza.aceptarPeticion');
Route::put('/datosalianza/eliminarPeticion2', [App\Http\Controllers\AlianzaController::class, 'eliminarPeticion2'])->middleware('can:Usuario_travian')->name('datosalianza.eliminarPeticion2');
Route::put('/datosalianza/dejarAli', [App\Http\Controllers\AlianzaController::class, 'dejarali'])->middleware('can:Usuario_travian')->name('datosalianza.dejarali');
Route::put('/datosalianza/dejarAli2', [App\Http\Controllers\AlianzaController::class, 'dejarali2'])->middleware('can:Usuario_travian')->name('datosalianza.dejarali2');


Route::put('/generarPush', [App\Http\Controllers\AlianzaController::class, 'generarPush'])->middleware('can:lider_alianza')->name('adminAlianza');

Route::get('/gestionpush', [App\Http\Controllers\AlianzaController::class, 'gestionpush'])->middleware('can:lider_alianza')->name('adminAlianza');

Route::get('/pushPendiente', [App\Http\Controllers\AlianzaController::class, 'pushPendiente'])->middleware('can:Usuario_travian')->name('pushPendiente');
Route::put('/pushPendiente/cerrarpush', [App\Http\Controllers\AlianzaController::class, 'cierrepush'])->middleware('can:Usuario_travian')->name('pushPendiente');

