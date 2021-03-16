<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'UserController@login');
Route::post('resetpassword', 'UserController@resetpassword');

// Route::post('usuario', 'UserController@store');

Route::group(['middleware' => 'auth:api'], function( $router ){
	
	Route::post('logout', 'UserController@logout');

	Route::get('me', function( Request $request ){ return $request->user(); });

	// Route::get('usuario', 'UserController@getAll');
	// Route::get('usuario/{id}', 'UserController@show');
	// Route::put('usuario/{id}', 'UserController@update');
	// Route::delete('usuario/{id}', 'UserController@destroy');

	Route::get('distribuidores', 'DistribuidorController@getAll');
	Route::get('distribuidor/{id}', 'DistribuidorController@show');

	Route::get('estados', 'EstadoController@get');
	Route::get('estado/{id}', 'EstadoController@show');
	Route::get('estado/{id}/cidades', 'EstadoController@cidades');

	Route::get('cidades', 'CidadeController@getAll');
	Route::get('cidade/{id}', 'CidadeController@show');
	Route::get('cidade/{id}/bairros', 'CidadeController@bairros');

	Route::get('pontos', 'PontoController@getAll');
	Route::get('ponto/{id}', 'PontoController@show');

	Route::get('dispositivos', 'DispositivoController@getAll');
	Route::get('dispositivo/{id}', 'DispositivoController@show');

	// Route::get('etapa', 'etapaController@show');
	// Route::get('vendas', 'VendaController@show');
	// matrizes?

});