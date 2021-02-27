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

Route::get('servico', 'ServicoController@getAll');
Route::post('servico/{id}/valor', 'ServicoController@valor');

Route::post('testPush', 'ServicoController@testPush');

Route::group(['middleware' => 'auth:api'], function( $router ){
	
	Route::post('logout', 'UserController@logout');

	Route::get('me', function( Request $request ){ return $request->user(); });

	Route::get('usuario', 'UserController@getAll');
	Route::get('usuario/{id}', 'UserController@show');
	Route::put('usuario/{id}', 'UserController@update');
	Route::delete('usuario/{id}', 'UserController@destroy');

	Route::get('perfil', 'PerfilController@getAll');
	Route::get('perfil/{id}', 'PerfilController@show');
	// Route::post('perfil', 'PerfilController@store');
	// Route::put('perfil/{id}', 'PerfilController@update');
	// Route::delete('perfil/{id}', 'PerfilController@destroy');

	Route::get('empresa', 'EmpresaController@getAll');
	Route::get('empresa/{id}', 'EmpresaController@show');
	// Route::post('empresa', 'EmpresaController@store');
	// Route::put('empresa/{id}', 'EmpresaController@update');
	// Route::delete('empresa/{id}', 'EmpresaController@destroy');

	Route::get('servico/{id}', 'ServicoController@show');
	// Route::post('servico', 'ServicoController@store');
	// Route::put('servico/{id}', 'ServicoController@update');
	// Route::delete('servico/{id}', 'ServicoController@destroy');
	Route::post('servico/contrato', 'ServicoController@contrato');
	Route::post('servico/recorrencia', 'ServicoController@recorrencia');
	Route::post('servico/{id}/contratar', 'ServicoController@contratar');
	Route::post('servico/{id}/recusar', 'ServicoController@recusarServico');
	Route::post('servico/notificarPrestadores', 'ServicoController@notificarPrestadores');
	
	Route::get('agendamento', 'ServicoController@agendamentos');
	Route::get('agendamento/{id}', 'ServicoController@agendamento');
	Route::put('agendamento/{id}/iniciar', 'ServicoController@iniciar');
	Route::post('agendamento/{id}/concluir', 'ServicoController@concluir');
	Route::put('agendamento/{id}/cancelar', 'ServicoController@cancelarServico');
	Route::post('agendamento/{id}/feedback', 'ServicoController@feedbackServico');
	Route::post('agendamento/{id}/reagendar', 'ServicoController@reagendar');

	Route::post('checkout', 'ServicoController@checkoutapp');

	Route::get('prestador', 'PrestadorController@getAll');
	Route::get('prestador/{id}', 'PrestadorController@show');
	Route::get('prestadores/proximos', 'PrestadorController@nasProximidades');
	Route::post('prestador', 'PrestadorController@store');
	Route::put('prestador/{id}', 'PrestadorController@update');
	Route::delete('prestador/{id}', 'PrestadorController@destroy');

	Route::get('cliente', 'ClienteController@getAll');
	Route::get('cliente/{id}', 'ClienteController@show');
	Route::post('cliente', 'ClienteController@store');
	Route::put('cliente/{id}', 'ClienteController@update');
	Route::delete('cliente/{id}', 'ClienteController@destroy');

	Route::get('mural', 'MuralController@getAll');
	Route::get('mural/{id}', 'MuralController@show');
	Route::post('mural', 'MuralController@store');
	Route::post('mural/{id}', 'MuralController@update');
	Route::delete('mural/{id}', 'MuralController@destroy');

	Route::get('faq', 'FaqController@getAll');
	Route::get('faq/{id}', 'FaqController@show');
	// Route::post('faq', 'FaqController@store');
	// Route::post('faq/{id}', 'FaqController@update');
	// Route::delete('faq/{id}', 'FaqController@destroy');

});