<?php

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false, 'reset' => false]);

Route::get('logout','Auth\LoginController@logout')->name('logout');

Route::group(['middleware' =>['auth','preventbackbutton']], function(){

    Route::get('/','HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('permisos','Acceso\PermisoController');
    Route::get('permisos-obtener','Acceso\PermisoController@obtener');

    Route::resource('roles','Acceso\RolController');
    Route::get('roles-permisos/{id}','Acceso\RolController@rol_permisos');
    Route::get('roles-obtener','Acceso\RolController@roles');

    /* Rutas de usuarios */
    Route::resource('usuarios','Acceso\UsuarioController');
    Route::resource('usuarios-roles','Acceso\UsuarioRolController',['except' =>['create','destroy']]);
    Route::get('usuario-rol/{id}','Acceso\UsuarioRolController@listado_roles');

    /* Catalogos */
    Route::resource('categorias','Administrar\CategoriaController');
    Route::resource('estados','Administrar\EstadoController');
    Route::resource('forma-pago','Administrar\FormaPagoController');
    Route::resource('proveedores', 'Administrar\ProveedorController');
    Route::resource('clientes','Administrar\ClienteController');
    Route::resource('productos','Administrar\ProductoController');
});