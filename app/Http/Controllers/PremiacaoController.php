<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Premiacao;
use Validator;
use DB;
use Session;
use App\Etapa;
use Illuminate\Support\Facades\Input;
class PremiacaoController extends Controller
{
    public function getAll( Request $request ){
        $premiacao = Premiacao::orderBy('etapa_id', 'DESC')->orderBy('seq', 'DESC')->paginate(10);
        return response()->json( $premiacao, 200 );
    }

    public function index( Request $request ){
        $etapas_ativas = [];
        foreach( [ 'semanal', 'mensal' ] as $tipo ){
            @$etp = Etapa::ativa($tipo)->id;
            if( $etp )
                $etapas_ativas[] = $etp;
        }
        $premiacoes = Premiacao::whereIn('etapa_id', $etapas_ativas)->orderBy('etapa_id')->orderBy('seq')->paginate(10);
        return view('premiacao.index',[ 'premiacoes' => $premiacoes ]);
    }

    public function create( Request $request ){

        if( ! Etapa::ativa() ){
            Session::flash('error', "Não existe etapa ativa!");
            return redirect('/etapas');
        }

        $etapas_ativas = [];
        $proximas_sequencias = [];
        foreach( [ 'semanal', 'mensal' ] as $tipo ){
            @$etp = Etapa::ativa($tipo);
            if( $etp ){
                $etapas_ativas[] = $etp;
                $proximas_sequencias[] = Premiacao::where('etapa_id', Etapa::ativa($tipo)->id)->max('seq') + 1;
            }
        }
        return view('premiacao.form',[ 'etapas_ativas' => $etapas_ativas, 'proximas_sequencias' => $proximas_sequencias ]);
    }

    public function store( Request $request ){

        $premiacao = Input::except( 'id', '_method', '_token' );
        $premiacao = Premiacao::create( $premiacao );

        return response()->json([
            'message' => 'Criado com sucesso',
            'redirectURL' => url('/premiacao'),
            'premiacao' => $premiacao
        ], 201 );
    }

    public function edit( Request $request, $id ){

        if( ! Etapa::ativa() ){
            Session::flash('error', "Não existe etapa ativa!");
            return redirect('/etapas');
        }

        $premiacao = Premiacao::findOrFail($id);
        $etapas_ativas = [];
        $proximas_sequencias = [];
        foreach( [ 'semanal', 'mensal' ] as $tipo ){
            @$etp = Etapa::ativa($tipo);
            if( $etp ){
                $etapas_ativas[] = $etp;
                $proximas_sequencias[] = Premiacao::where('etapa_id', Etapa::ativa($tipo)->id)->max('seq') + 1;
            }
        }

        return view('premiacao.form',['premiacao' => $premiacao, 'etapas_ativas' => $etapas_ativas, 'proximas_sequencias' => $proximas_sequencias]);
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

    public function show(Request $request, $id)
    {
        try {
            return response()->json(Premiacao::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
