<?php

namespace App\Http\Controllers;

use App\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CidadeController extends Controller
{

    public function getAll( Request $request ){
        $cidades = Cidade::orderBy('nome')->paginate(10);
        return response()->json( $cidades, 200 );
    }

    public function index( Request $request ){
        $cidades = Cidade::orderBy('nome')->paginate(10);
        return view('cidade.index',[ 'cidades' => $cidades ]);
    }

    public function create( Request $request ){
        return view('cidade.form');
    }
    
    public function store( Request $request ){
        $cidade = Cidade::create( Input::except( 'id', '_method', '_token' ) );
        return response()->json([ 
            'message' => 'Criado com sucesso', 
            'redirectURL' => url('/cidades'), 
            'cidade' => $cidade 
        ], 201 );
    }
    
    public function show( Request $request, $id ){
        try {
            return response()->json( Cidade::findOrFail($id) );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }
    
    public function edit( Request $request, $id ){
        $cidade = Cidade::findOrFail($id);
        return view('cidade.form',[ 'cidade' => $cidade ]);
    }
    
    public function update( Request $request, $id ){

        $cidade = Cidade::find($id);
        $inputs = Input::except( 'id', '_method', '_token' );
        foreach( $inputs as $key => $value ){
            $cidade->$key = $value;
        }
        $cidade->save();
        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/cidades'), 
            'cidade' => $cidade 
        ], 200 );
    }
    
    public function destroy( Request $request, $id ){
        $cidade = Cidade::findOrFail($id);
        $cidade->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
    
}
