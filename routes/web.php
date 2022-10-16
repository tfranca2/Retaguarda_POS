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

Route::get('/', 'VendaController@cartela');
Route::get('/home', function(){
	if( Auth::check() )
        return redirect()->route('home');
	return view('welcome');
});

Auth::routes();

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/comprovante/{key}', 'VendaController@comprovante');

Route::get('/correios', 'VendaController@correios');
Route::post('/correios', 'VendaController@correios');
Route::post('/correios-salvar', 'VendaController@correiosSalvar');
Route::post('/confirmarAtendimento', 'VendaController@correiosConfirmarAtendimento');

Route::get('/search/cidades', 'CidadeController@get');

Route::get('/cartela', 'VendaController@cartela');
Route::get('/prevenda', 'VendaController@prevenda');
Route::post('/prevenda', 'VendaController@prevendaconfirma');
Route::get('/checkout/{key}', 'VendaController@checkout');
Route::post('/checkout', 'VendaController@checkoutpagar');
Route::get('/checkout/{key}/pix', 'VendaController@pix');
Route::get('/pix/{key}', 'VendaController@getpixpayload');

Route::get('/visitor', 'HomeController@tracking');

Route::group(['middleware' => ['auth']], function(){

	Route::get('/home', 'HomeController@index')->name('home');

	Route::get('/perfil', 'UserController@perfil');
	Route::resource('usuarios', 'UserController');

	Route::resource('perfis', 'PerfilController');

	Route::get('/configuracoes', 'EmpresaController@configuracoes');
	Route::resource('empresas', 'EmpresaController');

	Route::resource('menus', 'MenuController');

	Route::resource('distribuidores', 'DistribuidorController');
	Route::get('distribuidores/{id}/dispositivos', 'DistribuidorController@dispositivos');

	Route::resource('matrizes', 'MatrizController');

	Route::resource('cidades', 'CidadeController');
	Route::get('/cidades/{id}/bairros', 'CidadeController@bairros');

	Route::resource('bairros', 'BairroController');

	Route::resource('pontos', 'PontoController');

	Route::get('dispositivos/{id}/toggleActive', 'DispositivoController@toggleActive');
	Route::resource('dispositivos', 'DispositivoController');

	Route::get('vendas/csv', 'VendaController@csv');
	Route::get('vendas/txt', 'VendaController@txt');
	Route::get('vendas/confirmar', 'VendaController@confirmar');
	Route::post('vendas/{id}/confirmar', 'VendaController@confirmar');
	Route::put('vendas/{id}/cancel', 'VendaController@cancel');
	Route::get('leads', 'VendaController@leads');
	Route::resource('vendas', 'VendaController');

	Route::resource('etapas', 'EtapaController');
	Route::get('etapas/{id}/ativar', 'EtapaController@ativar');

    Route::resource('premiacao', 'PremiacaoController');
    Route::resource('premiacaoeletronica', 'PremiacaoEletronicaController');

    Route::resource('cliente', 'ClienteController');
});
