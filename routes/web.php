<?php

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
//Rutas para el invitado
Route::group(['middleware' => ['guest']], function(){
	Route::get('/', 'Auth\LoginController@showLoginForm');
	Route::post('/login', 'Auth\LoginController@login')->name('login');
});

//Rutas para los usuarios authenticados
Route::group(['middleware' => ['auth']], function(){
 
	Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

	Route::get('/main', function () {
	    return view('contenido/contenido');
	})->name('main');

	//Auth::routes();

	//Route::get('/home', 'HomeController@index')->name('home');
	
	//Rutas para el Almacenero
	Route::group(['middleware' => ['Almacenero']], function(){
		Route::get('/categorias', 'CategoriaController@index');
		Route::post('/categorias/registrar', 'CategoriaController@store');
		Route::put('/categorias/actualizar', 'CategoriaController@update');
		Route::put('/categorias/desactivar', 'CategoriaController@desactivar');
		Route::put('/categorias/activar', 'CategoriaController@activar');
		Route::get('/categorias/selectCategoria', 'CategoriaController@selectCategoria');

		Route::get('/articulo', 'ArticuloController@index');
		Route::post('/articulo/registrar', 'ArticuloController@store');
		Route::put('/articulo/actualizar', 'ArticuloController@update');
		Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
		Route::put('/articulo/activar', 'ArticuloController@activar');
		Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
		Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');

		Route::get('/proveedor', 'ProveedorController@index');
		Route::post('/proveedor/registrar', 'ProveedorController@store');
		Route::put('/proveedor/actualizar', 'ProveedorController@update');
		Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

		Route::get('/ingreso', 'IngresoController@index');
		Route::post('/ingreso/registrar', 'IngresoController@store'); 
		Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
		Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
		Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');

	});

	//Rutas para el Vendedor
	Route::group(['middleware' => ['Vendedor']], function(){
		Route::get('/cliente', 'ClienteController@index');
		Route::post('/cliente/registrar', 'ClienteController@store');
		Route::put('/cliente/actualizar', 'ClienteController@update');

	});

	
	//Rutas para el Administrador
	Route::group(['middleware' => ['Administrador']], function(){
		Route::get('/categorias', 'CategoriaController@index');
		Route::post('/categorias/registrar', 'CategoriaController@store');
		Route::put('/categorias/actualizar', 'CategoriaController@update');
		Route::put('/categorias/desactivar', 'CategoriaController@desactivar');
		Route::put('/categorias/activar', 'CategoriaController@activar');
		Route::get('/categorias/selectCategoria', 'CategoriaController@selectCategoria');

		Route::get('/articulo', 'ArticuloController@index');
		Route::post('/articulo/registrar', 'ArticuloController@store');
		Route::put('/articulo/actualizar', 'ArticuloController@update');
		Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
		Route::put('/articulo/activar', 'ArticuloController@activar');
		Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
		Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');

		Route::get('/proveedor', 'ProveedorController@index');
		Route::post('/proveedor/registrar', 'ProveedorController@store');
		Route::put('/proveedor/actualizar', 'ProveedorController@update');
		Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

		Route::get('/cliente', 'ClienteController@index');
		Route::post('/cliente/registrar', 'ClienteController@store');
		Route::put('/cliente/actualizar', 'ClienteController@update');

		Route::get('/rol', 'RolController@index');
		Route::get('/rol/selectRol', 'RolController@selectRol');
					
		Route::get('/user', 'UserController@index');
		Route::post('/user/registrar', 'UserController@store');
		Route::put('/user/actualizar', 'UserController@update');
		Route::put('/user/desactivar', 'UserController@desactivar');
		Route::put('/user/activar', 'UserController@activar');

		Route::get('/ingreso', 'IngresoController@index');
		Route::post('/ingreso/registrar', 'IngresoController@store'); 
		Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
		Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
		Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');



	}); 
 
	


});
//Auth::routes();

 
