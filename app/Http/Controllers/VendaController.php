<?php

namespace App\Http\Controllers;

use App\Venda;
use App\Dispositivo;
use App\Etapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class VendaController extends Controller
{

    public function getAll( Request $request ){
        $vendas = Venda::paginate(10);
        return response()->json( $vendas, 200 );
    }

    public function index( Request $request ){
        $vendas = Venda::paginate(10);
        return view('venda.index',[ 'vendas' => $vendas ]);
    }

    public function create( Request $request ){
        $dispositivos = Dispositivo::get();
        $etapas = Etapa::get();
        return view('venda.form',[ 'dispositivos' => $dispositivos, 'etapas' => $etapas ]);
    }
    
    public function store( Request $request ){
        $venda = Venda::create( Input::except( 'id', '_method', '_token' ) );
        return response()->json([ 
            'message' => 'Criado com sucesso', 
            'redirectURL' => url('/vendas'), 
            'venda' => $venda 
        ], 201 );
    }
    
    public function show( Request $request, $id ){
        try {
            return response()->json( Venda::findOrFail($id) );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }
    
    public function edit( Request $request, $id ){
        $dispositivos = Dispositivo::get();
        $etapas = Etapa::get();
        $venda = Venda::findOrFail($id);
        return view('venda.form',[ 'venda' => $venda, 'dispositivos' => $dispositivos, 'etapas' => $etapas ]);
    }
    
    public function update( Request $request, $id ){

        $venda = Venda::find($id);
        $inputs = Input::except( 'id', '_method', '_token' );
        foreach( $inputs as $key => $value ){
            $venda->$key = $value;
        }
        $venda->save();
        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/vendas'), 
            'venda' => $venda 
        ], 200 );
    }
    
    public function destroy( Request $request, $id ){
        $venda = Venda::findOrFail($id);
        $venda->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
    
}
