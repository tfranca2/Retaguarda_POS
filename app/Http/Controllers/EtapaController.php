<?php

namespace App\Http\Controllers;

use Validator;
use DB;
use App\Etapa;
use App\Range;
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

        @$old_codigo_susep = DB::table('etapas')->select('codigo_susep')->whereNotNull('codigo_susep')->groupBy('codigo_susep')->orderByRaw('COUNT(*) DESC')->first()->codigo_susep;
        
        return view('etapa.form',[ 'prox_etapa' => $prox_etapa, 'old_codigo_susep' => $old_codigo_susep, 'tipos' => Etapa::TIPOS ]);
    }
    
    public function store( Request $request ){

        $validator = Validator::make($request->all(), [
            'etapa' => 'required|integer|unique:etapas,etapa',
            'frequencia' => 'required|string|in:semanal,mensal',
            'data' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $inputs = Input::only( 'etapa', 'descricao', 'data', 'frequencia', 'codigo_susep' );
        foreach( $inputs as $key => $value )
            $etapa[$key] = $value;

        $etapa = Etapa::create( $etapa );

        foreach( range( 0, count($request->tipo)-1 ) as $i => $rng ){
            Range::create([
                'etapa_id' => $etapa->id,
                'tipo' => Etapa::TIPOS[$request->tipo[$i]]['descricao'],
                'chances' => $request->chances[$i],
                'inicio' => $request->inicio[$i],
                'final' => $request->final[$i],
                'intervalo' => $request->intervalo[$i],
                'valor' => \Helper::formatDecimalToDb( $request->valor[$i] ),
                'comissao' => \Helper::formatDecimalToDb( $request->comissao[$i] ),
            ]);
        }

        return response()->json([ 
            'message' => 'Criado com sucesso', 
            'redirectURL' => url('/etapas'), 
            'etapa' => $etapa 
        ], 201 );
    }
    
    public function show( Request $request ){

        $validator = Validator::make($request->all(), [
            'id' => 'nullable|integer|exists:etapas,id',
        ]);
        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        try {
            $etapas=[];

            if( $request->has('id') ){
                $etapas[] = Etapa::with('premiacao')->with('premiacaoEletronica')->findOrFail($request->id);
            } else {
                foreach( ['semanal', 'mensal'] as $frequencia ){
                    $etapaAtiva = Etapa::ativa( $frequencia );
                    if( !$etapaAtiva )
                        continue;

                    $etapas[] = Etapa::with('premiacao')->with('premiacaoEletronica')->findOrFail($etapaAtiva->id);
                }
            }

            return response()->json($etapas);
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }
    
    public function edit( Request $request, $id ){
        $etapa = Etapa::findOrFail($id);
        $old_codigo_susep = $etapa->codigo_susep;
        return view('etapa.form',[ 'etapa' => $etapa, 'old_codigo_susep' => $old_codigo_susep, 'tipos' => Etapa::TIPOS ]);
    }
    
    public function update( Request $request, $id ){

        $validator = Validator::make($request->all(), [
            'etapa' => 'required|integer|unique:etapas,etapa,'. $id,
            'frequencia' => 'required|string|in:semanal,mensal',
            'data' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $etapa = Etapa::find($id);
        $inputs = Input::only( 'etapa', 'descricao', 'data', 'frequencia', 'codigo_susep' );
        foreach( $inputs as $key => $value ){
            $etapa->$key = $value;
        }

        $etapa->save();
        $etapa->ranges()->delete();
        foreach( range( 0, count($request->tipo)-1 ) as $i => $rng ){
            Range::create([
                'etapa_id' => $etapa->id,
                'tipo' => Etapa::TIPOS[$request->tipo[$i]]['descricao'],
                'chances' => $request->chances[$i],
                'inicio' => $request->inicio[$i],
                'final' => $request->final[$i],
                'intervalo' => intval($request->intervalo[$i]),
                'valor' => \Helper::formatDecimalToDb( $request->valor[$i] ),
                'comissao' => \Helper::formatDecimalToDb( $request->comissao[$i] ),
            ]);
        }

        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/etapas'), 
            'etapa' => $etapa 
        ], 200 );
    }
    
    public function destroy( Request $request, $id ){
        $etapa = Etapa::findOrFail($id);
        $etapa->ranges()->delete();
        $etapa->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }

    public function ativar( Request $request, $id ){
        
        $etapa = Etapa::findOrFail($id);

        $idEtapaAtiva = 0;
        $etapaAtiva = Etapa::where('ativa','1')->where('frequencia', $etapa->frequencia)->first();
        if( $etapaAtiva )
            $idEtapaAtiva = $etapaAtiva->id;

        @$dataMaisRecente = Etapa::whereRaw('data > NOW()')->where('frequencia', $etapa->frequencia)->orderBy('data')->first()->data;

        if( strtotime( $etapa->data ) < strtotime( $dataMaisRecente ) and $id != $idEtapaAtiva )
            return response()->json([ 'error' => 'Essa não é a etapa mais recente!', 'id' => $idEtapaAtiva ], 400 );

        DB::table('etapas')->where('frequencia', $etapa->frequencia)->update(array('ativa' => 0));

        $etapa->ativa = true;
        $etapa->save();

        $resp = [ 'id' => $id ];
        if( $id != $idEtapaAtiva )
            $resp['message'] = [ 'Etapa ativada' ];

        return response()->json($resp,200);

    }

}
