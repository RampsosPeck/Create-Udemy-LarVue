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

Route::get('/', function () {
    return view('contenido/contenido');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


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


Route::get('/cliente', 'ClienteController@index');
Route::post('/cliente/registrar', 'ClienteController@store');
Route::put('/cliente/actualizar', 'ClienteController@update');


Route::get('/proveedor', 'ProveedorController@index');
Route::post('/proveedor/registrar', 'ProveedorController@store');
Route::put('/proveedor/actualizar', 'ProveedorController@update');

Route::get('/rol', 'RolController@index');
Route::get('/rol/selectRol', 'RolController@selectRol');
			
Route::get('/user', 'UserController@index');
Route::post('/user/registrar', 'UserController@store');
Route::put('/user/actualizar', 'UserController@update');
Route::put('/user/desactivar', 'UserController@desactivar');
Route::put('/user/activar', 'UserController@activar');


