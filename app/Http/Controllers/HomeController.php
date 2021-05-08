<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Etapa;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){

        $etapa = Etapa::ativa();

        $json = array(
            'vendas' => [ // qtd
                'total' => 0, 
                'cartao' => 0, 
                'manual' => 0, 
                'porcentagem' => 0, 
            ],
            'total' => [ // R$
                'total' => 0.00, 
                'cartao' => 0.00, 
                'manual' => 0.00, 
                'porcentagem' => 0, 
            ],
            'app' => [
                'total' => [ 
                    'valor' => 0.00, // R$
                    'porcentagem' => 0, 
                ],
                'quantidade' => [ 
                    'valor' => 0, // qtd
                    'porcentagem' => 0, 
                ],
            ],
            'web' => [
                'total' => [ 
                    'valor' => 0.00, // R$
                    'porcentagem' => 0, 
                ],
                'quantidade' => [ 
                    'valor' => 0, // qtd
                    'porcentagem' => 0, 
                ],
            ],
            'vendedor' => [
                'vendas' => [
                    'valor' => 0, // qtd
                    'porcentagem' => 0, 
                ],
                'total' => [
                    'valor' => 0, // R$
                    'porcentagem' => 0, 
                ],
            ],
            'direta' => [
                'vendas' => [
                    'valor' => 0, // qtd
                    'porcentagem' => 0, 
                ],
                'total' => [
                    'valor' => 0, // R$
                    'porcentagem' => 0, 
                ],
            ],
        );

        $faturamento = array(
            'Edição 35' => 4507.62,
            'Edição 36' => 5902.45,
            'Edição 37' => 3879.38,
            'Edição 38' => 5712.77,
            'Edição 39' => 6238.12,
        );

        return view('home', [
            'json' => $json,
            'faturamento' => $faturamento,
        ]);

    }

}
