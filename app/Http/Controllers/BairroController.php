<?php

namespace App\Http\Controllers;

use App\Bairro;
use App\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BairroController extends Controller
{

    public function getAll( Request $request ){
        $bairros = Bairro::orderBy('nome')->paginate(10);
        return response()->json( $bairros, 200 );
    }

    public function index( Request $request ){
        $bairros = Bairro::orderBy('nome')->paginate(10);
        return view('bairro.index',[ 'bairros' => $bairros ]);
    }

    public function create( Request $request ){
        $cidades = Cidade::get();
        return view('bairro.form',[ 'cidades' => $cidades ]);
    }

    public function store( Request $request ){
        $bairro = Bairro::create( Input::except( 'id', '_method', '_token' ) );
        return response()->json([
            'message' => 'Criado com sucesso',
            'redirectURL' => url('/bairros'),
            'bairro' => $bairro
        ], 201 );
    }

    public function show( Request $request, $id ){
        try {
            return response()->json( Bairro::findOrFail($id) );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }

    public function edit( Request $request, $id ){
        $bairro = Bairro::findOrFail($id);
        $cidades = Cidade::get();
        return view('bairro.form',[ 'bairro' => $bairro, 'cidades' => $cidades ]);
    }

    public function update( Request $request, $id ){

        $bairro = Bairro::find($id);
        $inputs = Input::except( 'id', '_method', '_token' );
        foreach( $inputs as $key => $value ){
            $bairro->$key = $value;
        }
        $bairro->save();
        return response()->json([
            'message' => 'Atualizado com sucesso',
            'redirectURL' => url('/bairros'),
            'bairro' => $bairro
        ], 200 );
    }

    public function destroy( Request $request, $id ){
        $bairro = Bairro::findOrFail($id);
        $bairro->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }

}
