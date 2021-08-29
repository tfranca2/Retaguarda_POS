<?php

namespace App\Http\Controllers;

use App\Cidade;
use Illuminate\Http\Request;
use App\Cliente;
use Illuminate\Support\Facades\Input;


class ClienteController extends Controller
{
    public function getAll( Request $request ){
        $premiacao = Cliente::orderBy('seq', 'DESC')->paginate(10);
        return response()->json( $premiacao, 200 );
    }

    public function index( Request $request ){
        $clientes = Cliente::get();
        $cidades = Cidade::get();
        return view('cliente.index',[ 'clientes' => $clientes,'cidades' => $cidades]);
    }
    public function create( Request $request ){
        $cidades = Cidade::get();
        return view('cliente.form',[ 'cidades' => $cidades ]);
    }
    public function store( Request $request ){
        $cliente = Cliente::create( Input::except( 'id', '_method', '_token' ) );
        return response()->json([
            'message' => 'Criado com sucesso',
            'redirectURL' => url('/cliente'),
            'premiacao' => $cliente
        ], 201 );
    }
    public function edit( Request $request, $id ){
        $etapaAtiva = Etapa::ativa();
        $premiacao = Cliente::findOrFail($id);
        return view('premiacao.form',[ 'premiacao' => $premiacao,'etapaAtiva' => $etapaAtiva]);
    }

    public function update( Request $request, $id ){

        $premiacao = Cliente::find($id);
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
        $premiacao = Cliente::findOrFail($id);
        $premiacao->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }

}
