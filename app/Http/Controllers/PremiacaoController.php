<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Premiacao;
use Validator;
use DB;
use App\Etapa;
use Illuminate\Support\Facades\Input;
class PremiacaoController extends Controller
{

    public function getAll( Request $request ){
        $premiacao = Premiacao::orderBy('seq', 'DESC')->paginate(10);
        return response()->json( $premiacao, 200 );
    }

    public function index( Request $request ){
        $premiacoes = Premiacao::orderBy('seq')->paginate(10);
        $etapaAtiva = Etapa::ativa();
        return view('premiacao.index',[ 'premiacoes' => $premiacoes,'etapaAtiva' => $etapaAtiva]);
    }
    public function create( Request $request ){
        $etapaAtiva = Etapa::ativa();
        $prox_etapa = DB::table('etapas')->latest()->first()->etapa;
        return view('premiacao.form',[ 'prox_etapa' => $prox_etapa,'etapaAtiva' => $etapaAtiva]);
    }
    public function store( Request $request ){
        $premiacao = Premiacao::create( Input::except( 'id', '_method', '_token' ) );
        return response()->json([
            'message' => 'Criado com sucesso',
            'redirectURL' => url('/premiacao'),
            'premiacao' => $premiacao
        ], 201 );
    }
    public function edit( Request $request, $id ){
        $etapaAtiva = Etapa::ativa();
        $premiacao = Premiacao::findOrFail($id);
        return view('premiacao.form',[ 'premiacao' => $premiacao,'etapaAtiva' => $etapaAtiva]);
    }

    public function update( Request $request, $id ){

        $premiacao = Premiacao::find($id);
        $inputs = Input::except( 'id', '_method', '_token' );
        foreach( $inputs as $key => $value ){
            $premiacao->$key = $value;
        }
        $premiacao->save();
        return response()->json([
            'message' => 'Atualizado com sucesso',
            'redirectURL' => url('/premiacao'),
            'premiacao' => $premiacao
        ], 200 );
    }
    public function destroy( Request $request, $id ){
        $premiacao = Premiacao::findOrFail($id);
        $premiacao->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
}
