<?php

namespace App\Http\Controllers;

use App\Etapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class EtapaController extends Controller
{

    public function getAll( Request $request ){
        $etapas = Etapa::orderBy('etapa')->paginate(10);
        return response()->json( $etapas, 200 );
    }

    public function index( Request $request ){
        $etapas = Etapa::orderBy('descricao')->paginate(10);
        return view('etapa.index',[ 'etapas' => $etapas ]);
    }

    public function create( Request $request ){
        return view('etapa.form');
    }
    
    public function store( Request $request ){

        $inputs = Input::except( 'id', '_method', '_token', 'valor_simples', 'valor_duplo', 'valor_triplo', 'v_comissao_simples', 'v_comissao_duplo', 'v_comissao_triplo' );
        foreach( $inputs as $key => $value )
            $etapa[$key] = $value;

        $etapa['valor_simples'] = \Helper::formatDecimalToDb($request->valor_simples);
        $etapa['valor_duplo'] = \Helper::formatDecimalToDb($request->valor_duplo);
        $etapa['valor_triplo'] = \Helper::formatDecimalToDb($request->valor_triplo);
        $etapa['v_comissao_simples'] = \Helper::formatDecimalToDb($request->v_comissao_simples);
        $etapa['v_comissao_duplo'] = \Helper::formatDecimalToDb($request->v_comissao_duplo);
        $etapa['v_comissao_triplo'] = \Helper::formatDecimalToDb($request->v_comissao_triplo);

        $etapa = Etapa::create( $etapa );
        return response()->json([ 
            'message' => 'Criado com sucesso', 
            'redirectURL' => url('/etapas'), 
            'etapa' => $etapa 
        ], 201 );
    }
    
    public function show( Request $request, $id ){
        try {
            return response()->json( Etapa::findOrFail($id) );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }
    
    public function edit( Request $request, $id ){
        $etapa = Etapa::findOrFail($id);
        return view('etapa.form',[ 'etapa' => $etapa ]);
    }
    
    public function update( Request $request, $id ){

        $etapa = Etapa::find($id);
        $inputs = Input::except( 'id', '_method', '_token', 'valor_simples', 'valor_duplo', 'valor_triplo', 'v_comissao_simples', 'v_comissao_duplo', 'v_comissao_triplo' );
        foreach( $inputs as $key => $value ){
            $etapa->$key = $value;
        }

        $etapa->valor_simples = \Helper::formatDecimalToDb($request->valor_simples);
        $etapa->valor_duplo = \Helper::formatDecimalToDb($request->valor_duplo);
        $etapa->valor_triplo = \Helper::formatDecimalToDb($request->valor_triplo);
        $etapa->v_comissao_simples = \Helper::formatDecimalToDb($request->v_comissao_simples);
        $etapa->v_comissao_duplo = \Helper::formatDecimalToDb($request->v_comissao_duplo);
        $etapa->v_comissao_triplo = \Helper::formatDecimalToDb($request->v_comissao_triplo);

        $etapa->save();
        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/etapas'), 
            'etapa' => $etapa 
        ], 200 );
    }
    
    public function destroy( Request $request, $id ){
        $etapa = Etapa::findOrFail($id);
        $etapa->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
    
}
