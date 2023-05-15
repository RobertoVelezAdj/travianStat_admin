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
Route::put('/AdminPermisos/Eliminar', [App\Http\Controllers\Controller_admin_permisos::class, 'EliminarPermiso'])->middleware('can:Administrador')->name('AdminPermisos.Eliminar');

//Admin parametrizaciones
Route::get('/AdminParametrizaciones', [App\Http\Controllers\Controller_admin_parametrizaciones::class, 'index'])->middleware('can:Administrador')->name('Parametrizaciones'); 
