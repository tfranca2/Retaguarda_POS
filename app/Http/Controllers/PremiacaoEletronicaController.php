<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PremiacaoEletronica;
use Validator;
use DB;
use Session;
use App\Etapa;
use Illuminate\Support\Facades\Input;

class PremiacaoEletronicaController extends Controller
{
    public function getAll( Request $request ){
        $premiacao = premiacaoEletronica::orderBy('etapa_id', 'DESC')->orderBy('numero', 'ASC')->paginate(10);
        return response()->json( $premiacao, 200 );
    }

    public function index(Request $request)
    {
        $etapas_ativas = [];
        foreach( [ 'semanal', 'mensal' ] as $tipo ){
            @$etp = Etapa::ativa($tipo)->id;
            if( $etp )
                $etapas_ativas[] = $etp;
        }
        $premiacoes = premiacaoEletronica::whereIn('etapa_id', $etapas_ativas)->orderBy('etapa_id')->orderBy('numero')->paginate(10);
        return view('premiacaoEletronica.index', ['premiacoes' => $premiacoes]);
    }

    public function create(Request $request)
    {

        if( ! Etapa::ativa() ){
            Session::flash('error', "Não existe etapa ativa!");
            return redirect('/etapas');
        }

        $etapas_ativas = [];
        foreach( [ 'semanal', 'mensal' ] as $tipo ){
            @$etp = Etapa::ativa($tipo);
            if( $etp )
                $etapas_ativas[] = $etp;
        }
        return view('premiacaoEletronica.form',['etapas_ativas' => $etapas_ativas]);
    }

    public function store(Request $request)
    {
        $quantidadePremios = Input::get('numero');
        for ($i = 1; $i <= $quantidadePremios; $i++) {
            $premiacao = Input::except('id', '_method', '_token');
            $premiacao['numero'] = $i;
            $premiacao = PremiacaoEletronica::create($premiacao);
        }
        
        return response()->json([
            'message' => 'Criado com sucesso',
            'redirectURL' => url('/premiacaoeletronica'),
            'premiacao' => $premiacao
        ], 201);
    }

    public function edit(Request $request, $id)
    {

        if( ! Etapa::ativa() ){
            Session::flash('error', "Não existe etapa ativa!");
            return redirect('/etapas');
        }

        $premiacao = premiacaoEletronica::findOrFail($id);
        $etapas_ativas = [];
        foreach( [ 'semanal', 'mensal' ] as $tipo ){
            @$etp = Etapa::ativa($tipo);
            if( $etp )
                $etapas_ativas[] = $etp;
        }
        return view('premiacaoEletronica.form', ['premiacao' => $premiacao, 'etapas_ativas' => $etapas_ativas]);
    }

    public function update(Request $request, $id)
    {
        $premiacao = premiacaoEletronica::find($id);
        $inputs = Input::except('id', '_method', '_token');
        foreach ($inputs as $key => $value) {
            $premiacao->$key = $value;
        }
        $premiacao->save();
        return response()->json([
            'message' => 'Atualizado com sucesso',
            'redirectURL' => url('/premiacaoeletronica'),
            'premiacao' => $premiacao
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $premiacaoEletronica = premiacaoEletronica::findOrFail($id);
        $premiacaoEletronica->delete();

        return response()->json(['message' => 'Deletado com sucesso'], 204);
    }

    public function show(Request $request, $id)
    {
        try {
            return response()->json(premiacaoEletronica::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
