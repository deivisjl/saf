<?php

use Illuminate\Support\Facades\DB;
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

    /* Compras */
    Route::resource('compras','Compra\CompraController',['only' =>['index','show','create','store']]);

    /* Ventas */
    Route::resource('ventas','Venta\VentaController',['only' =>['index','show','create','store']]);

    /* Inventario */
    Route::resource('inventario','Inventario\InventarioController',['only' =>['index','show']]);

    /* Pago a proveedores */
    Route::resource('pago-proveedores','Pagos\PagoProveedorController',['only' => ['index','show','store']]);
    Route::get('pago-proveedores/{id}/detalle','Pagos\PagoProveedorController@detalle');
    Route::get('pago-proveedores/{id}/abonar','Pagos\PagoProveedorController@abonar');

    /* Pago de clientes */
    Route::resource('pago-clientes','Pagos\PagoClienteController',['only' => ['index','show','store']]);
    Route::get('pago-clientes/{id}/detalle','Pagos\PagoClienteController@detalle');
    Route::get('pago-clientes/{id}/abonar','Pagos\PagoClienteController@abonar');

    /* Reportes graficos */
    Route::get('reportes-graficos','Reportes\ReporteGraficoController@index')->name('reportes-graficos.index');
    Route::post('compras-por-categoria','Reportes\ReporteGraficoController@comprasPorCategoria');
    Route::post('ventas-por-categoria','Reportes\ReporteGraficoController@ventasPorCategoria');
    Route::post('categorias-mas-vendidas','Reportes\ReporteGraficoController@categoriasMasVendidas');
    Route::post('existencias-en-inventario','Reportes\ReporteGraficoController@existenciasEnInventario');
    Route::post('ventas-por-mes','Reportes\ReporteGraficoController@ventasPorMes');
});