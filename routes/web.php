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

Route::post('servicos/checkout/callback', 'ServicoController@checkoutCallback');
Route::get('servicos/checkout/callback', 'ServicoController@checkoutCallback');

Route::get('checkout', 'ServicoController@checkout2');
Route::post('servicos/checkout', 'ServicoController@checkoutPagar');
Route::get('success', function(){ return view('layouts.public'); });
Route::get('termo-cancelamento', function(){ return view('termo-cancelamento'); });
Route::get('faqs', 'FaqController@indexApp');

Route::group(['middleware' => ['auth']], function(){

	Route::get('/perfil', 'UserController@perfil');
	Route::resource('usuarios', 'UserController');

	Route::resource('perfis', 'PerfilController');

	Route::get('/configuracoes', 'EmpresaController@configuracoes');
	Route::resource('empresas', 'EmpresaController');
	
	Route::get('servicos/{id}/mostrar', 'ServicoController@mostrar');
	Route::get('servicos/{id}/carregarCampos', 'ServicoController@carregarCampos');
	Route::get('servicos/contratacao', 'ServicoController@contratacao');
	Route::post('servicos/contrato', 'ServicoController@contrato');
	Route::post('servicos/contratar', 'ServicoController@contratar');
	Route::get('servicos/calendario', 'ServicoController@calendario');
	Route::get('servicos/calendario/agendamentos', 'ServicoController@agendamentos');
	Route::get('servicos/calendario/agendamentos/{id}', 'ServicoController@agendamento');
	Route::get('servicos/calendario/agendamentos/{id}/iniciar', 'ServicoController@iniciar');
	Route::post('servicos/calendario/agendamentos/concluir', 'ServicoController@concluir');
	Route::post('servicos/calendario/agendamentos/{id}/concluir', 'ServicoController@concluir');
	Route::post('servicos/{id}/valor', 'ServicoController@valor');

	Route::get('servicos/checkout', 'ServicoController@checkout');
	// Route::post('servicos/checkout', 'ServicoController@checkoutPagar');
	
	Route::get('pagamentos', 'ServicoController@pagamentos');
	Route::put('pagamento', 'ServicoController@cancelarPagamento');
	Route::get('contratacao/{id}/edit', 'ServicoController@reagendamento');
	Route::put('contratacao/{id}', 'ServicoController@reagendar');

	Route::resource('servicos', 'ServicoController');

	Route::get('prestadores/proximos', 'PrestadorController@nasProximidades');
	Route::get('prestadores/{id}/relatorio', 'PrestadorController@relatorio');
	Route::get('prestadores/{id}/csv', 'PrestadorController@csv');
	Route::resource('prestadores', 'PrestadorController');

	Route::resource('clientes', 'ClienteController');

	Route::resource('faq', 'FaqController');

});