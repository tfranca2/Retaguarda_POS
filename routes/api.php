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

Route::post('usuario', 'UserController@store');

Route::group(['middleware' => 'auth:api'], function( $router ){
	
	Route::post('logout', 'UserController@logout');

	Route::get('me', function( Request $request ){ return $request->user(); });

	Route::get('usuario', 'UserController@getAll');
	Route::get('usuario/{id}', 'UserController@show');
	Route::put('usuario/{id}', 'UserController@update');
	Route::delete('usuario/{id}', 'UserController@destroy');

	Route::get('perfil', 'PerfilController@getAll');
	Route::get('perfil/{id}', 'PerfilController@show');

	Route::get('empresa', 'EmpresaController@getAll');
	Route::get('empresa/{id}', 'EmpresaController@show');

	Route::get('distribuidor', 'DistribuidorController@getAll');
	Route::get('distribuidor/{id}', 'DistribuidorController@show');
	Route::post('distribuidor', 'DistribuidorController@store');
	Route::put('distribuidor/{id}', 'DistribuidorController@update');
	Route::delete('distribuidor/{id}', 'DistribuidorController@destroy');

});