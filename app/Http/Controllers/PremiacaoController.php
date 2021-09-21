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
        $premiacao = Premiacao::orderBy('etapa_id', 'DESC')->orderBy('seq', 'DESC')->paginate(10);
        return response()->json( $premiacao, 200 );
    }

    public function index( Request $request ){
        $premiacoes = Premiacao::where('etapa_id', Etapa::ativa()->id)->orderBy('seq')->paginate(10);
        return view('premiacao.index',[ 'premiacoes' => $premiacoes ]);
    }

    public function create( Request $request ){
        $prox_seq = Premiacao::where('etapa_id', Etapa::ativa()->id)->max('seq') + 1;
        return view('premiacao.form',[ 'etapa' => Etapa::ativa(), 'prox_seq' => $prox_seq ]);
    }

    public function store( Request $request ){
        $premiacao = Premiacao::create( Input::except( 'id', '_method', '_token' ) );
        $premiacao->etapa_id = Etapa::ativa()->id;

        return response()->json([
            'message' => 'Criado com sucesso',
            'redirectURL' => url('/premiacao'),
            'premiacao' => $premiacao
        ], 201 );
    }

    public function edit( Request $request, $id ){
        $premiacao = Premiacao::findOrFail($id);
        $etapa = $premiacao->etapa;

        return view('premiacao.form',['premiacao' => $premiacao, 'etapa' => $etapa]);
    }

    public function update( Request $request, $id ){
        $premiacao = Premiacao::find($id);
        $inputs = Input::except( 'id', 'etapa_id', '_method', '_token' );
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

    public function show(Request $request, $id)
    {
        try {
            return response()->json(Premiacao::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
