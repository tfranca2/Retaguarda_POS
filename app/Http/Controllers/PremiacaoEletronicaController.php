<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PremiacaoEletronica;
use Validator;
use DB;
use App\Etapa;
use Illuminate\Support\Facades\Input;

class PremiacaoEletronicaController extends Controller
{
    public function index(Request $request)
    {
        $etapaAtiva = Etapa::ativa();
        $premiacoes = premiacaoEletronica::orderBy('numero')->paginate(10);
        return view('premiacaoEletronica.index', ['premiacoes' => $premiacoes,'etapaAtiva' => $etapaAtiva]);
    }

    public function create(Request $request)
    {
        $etapaAtiva = Etapa::ativa();
        $prox_etapa = DB::table('etapas')->latest()->first()->etapa;
        return view('premiacaoEletronica.form',[ 'prox_etapa' => $prox_etapa,'etapaAtiva' => $etapaAtiva]);
    }

    public function store(Request $request)
    {
        $quantidadePremios = Input::get('numero');
        for ($i = 1; $i <= $quantidadePremios; $i++) {
            $premiacao = PremiacaoEletronica::create(Input::except('id', '_method', '_token'));
            $premiacao->numero = $i;
            $premiacao->save();
        }
        return response()->json([
            'message' => 'Criado com sucesso',
            'redirectURL' => url('/premiacaoeletronica'),
            'premiacao' => $premiacao
        ], 201);
    }

    public function edit(Request $request, $id)
    {
        $etapaAtiva = Etapa::ativa();
        $premiacao = premiacaoEletronica::findOrFail($id);
        return view('premiacaoEletronica.form', ['premiacao' => $premiacao,'etapaAtiva' => $etapaAtiva]);
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
            'redirectURL' => url('/premiacaoEletronica'),
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
