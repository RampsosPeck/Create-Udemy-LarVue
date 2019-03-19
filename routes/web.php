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


