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

Route::get('/', function(){ return view('welcome'); });

Auth::routes();

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function(){

	Route::get('/perfil', 'UserController@perfil');
	Route::resource('usuarios', 'UserController');

	Route::resource('perfis', 'PerfilController');

	Route::get('/configuracoes', 'EmpresaController@configuracoes');
	Route::resource('empresas', 'EmpresaController');

	Route::resource('menus', 'MenuController');

	Route::resource('distribuidores', 'DistribuidorController');
	Route::resource('matrizes', 'MatrizController');

	Route::resource('cidades', 'CidadeController');
	Route::get('/search/cidades', 'CidadeController@get');

	Route::resource('bairros', 'BairroController');
	Route::resource('pontos', 'PontoController');
	Route::resource('etapas', 'EtapaController');
	Route::resource('dispositivos', 'DispositivoController');
	Route::resource('vendas', 'VendaController');

});