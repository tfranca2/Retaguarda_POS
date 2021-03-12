<?php

namespace App\Http\Controllers;

use App\Ponto;
use App\Cidade;
use App\Bairro;
use App\Distribuidor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PontoController extends Controller
{

    public function getAll( Request $request ){
        $pontos = Ponto::orderBy('nome')->paginate(10);
        return response()->json( $pontos, 200 );
    }

    public function index( Request $request ){
        $pontos = Ponto::orderBy('nome')->paginate(10);
        return view('ponto.index',[ 'pontos' => $pontos ]);
    }

    public function create( Request $request ){
        $cidades = Cidade::get();
        $bairros = Bairro::get();
        $distribuidores = Distribuidor::get();
        return view('ponto.form', [ 'cidades' => $cidades, 'bairros' => $bairros, 'distribuidores' => $distribuidores ]);
    }
    
    public function store( Request $request ){
        $ponto = Ponto::create( Input::except( 'id', '_method', '_token' ) );
        return response()->json([ 
            'message' => 'Criado com sucesso', 
            'redirectURL' => url('/pontos'), 
            'ponto' => $ponto 
        ], 201 );
    }
    
    public function show( Request $request, $id ){
        try {
            return response()->json( Ponto::findOrFail($id) );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }
    
    public function edit( Request $request, $id ){
        $cidades = Cidade::get();
        $bairros = Bairro::get();
        $distribuidores = Distribuidor::get();
        $ponto = Ponto::findOrFail($id);
        return view('ponto.form', [ 'ponto' => $ponto, 'cidades' => $cidades, 'bairros' => $bairros, 'distribuidores' => $distribuidores ]);
    }
    
    public function update( Request $request, $id ){

        $ponto = Ponto::find($id);
        $inputs = Input::except( 'id', '_method', '_token' );
        foreach( $inputs as $key => $value ){
            $ponto->$key = $value;
        }
        $ponto->save();
        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/pontos'), 
            'ponto' => $ponto 
        ], 200 );
    }
    
    public function destroy( Request $request, $id ){
        $ponto = Ponto::findOrFail($id);
        $ponto->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
    
}
