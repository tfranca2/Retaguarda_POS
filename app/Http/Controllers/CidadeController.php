<?php

namespace App\Http\Controllers;

use App\Cidade;
use App\Bairro;
use App\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CidadeController extends Controller
{

    public function getAll( Request $request ){
        $cidades = Cidade::orderBy('nome')->paginate(10);
        return response()->json( $cidades, 200 );
    }

    public function get( Request $request ){
        if($request->has('term')){
            $cidades = Cidade::select('cidades.id')
                        ->selectRaw('CONCAT(cidades.nome," - ",estados.uf) AS name')
                        ->where('cidades.nome','like','%'.$request->term.'%')
                        ->orWhere('estados.nome','like','%'.$request->term.'%')
                        ->leftJoin('estados', 'cidades.estado_id', '=', 'estados.id')
                        ->orderBy('estados.nome')
                        ->orderBy('cidades.nome')
                        ->get();

            return response()->json( $cidades, 200 );
        }
        return response()->json( [], 200 );
    }

    public function index( Request $request ){
        $cidades = Cidade::orderBy('nome')->paginate(10);
        return view('cidade.index',[ 'cidades' => $cidades ]);
    }

    public function create( Request $request ){
        return view('cidade.form',['estados'=>Estado::all()]);
    }
    
    public function store( Request $request ){
        $cidade = Cidade::create( Input::except( 'id', '_method', '_token' ) );
        $bairro = Bairro::create([ 'nome' => 'Centro', 'cidade_id' => $cidade->id ]);
        // INSERT INTO bairros ( nome, cidade_id ) SELECT 'Centro', cidades.id FROM cidades WHERE cidades.id NOT IN ( SELECT DISTINCT cidade_id FROM bairros );
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
        return view('cidade.form',[ 'cidade' => $cidade, 'estados' => Estado::all() ]);
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
