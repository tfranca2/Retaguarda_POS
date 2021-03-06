<?php

namespace App\Http\Controllers;

use Validator;
use DB;
use App\Etapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class EtapaController extends Controller
{

    public function getAll( Request $request ){
        $etapas = Etapa::orderBy('etapa', 'DESC')->paginate(10);
        return response()->json( $etapas, 200 );
    }

    public function index( Request $request ){
        $etapas = Etapa::orderBy('etapa', 'DESC')->paginate(10);
        return view('etapa.index',[ 'etapas' => $etapas ]);
    }

    public function create( Request $request ){
        $prox_etapa = DB::table('etapas')->latest()->first()->etapa + 1;
        return view('etapa.form',[ 'prox_etapa' => $prox_etapa ]);
    }
    
    public function store( Request $request ){

        $validator = Validator::make($request->all(), [
            'etapa' => 'required|integer',
            'data' => 'required|date|after:today',
            'range_inicial' => 'required|integer',
            'range_final' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

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

        $validator = Validator::make($request->all(), [
            'etapa' => 'required|integer',
            'data' => 'required|date|after:today',
            'range_inicial' => 'required|integer',
            'range_final' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

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

    public function ativar( Request $request, $id ){
        
        $idEtapaAtiva = 0;
        $etapaAtiva = Etapa::where('ativa','1')->first();
        if( $etapaAtiva )
            $idEtapaAtiva = $etapaAtiva->id;

        $etapa = Etapa::findOrFail($id);
        $dataMaisRecente = Etapa::whereRaw('data > NOW()')->orderBy('data')->first()->data;

        if( strtotime( $etapa->data ) < strtotime( $dataMaisRecente ) and $id != $idEtapaAtiva )
            return response()->json([ 'error' => 'Essa não é a etapa mais recente!', 'id' => $idEtapaAtiva ], 400 );

        DB::table('etapas')->update(array('ativa' => 0));

        $etapa->ativa = true;
        $etapa->save();

        $resp = [ 'id' => $id ];
        if( $id != $idEtapaAtiva )
            $resp['message'] = [ 'Etapa ativada' ];

        return response()->json($resp,200);

    }
    
}
