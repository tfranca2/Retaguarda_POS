<?php

namespace App\Http\Controllers;

use App\Dispositivo;
use App\Distribuidor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DispositivoController extends Controller
{

    public function getAll( Request $request ){
        $dispositivos = Dispositivo::withTrashed()->orderBy('nome')->paginate(10);
        return response()->json( $dispositivos, 200 );
    }

    public function index( Request $request ){
        $dispositivos = Dispositivo::withTrashed()->orderBy('nome')->paginate(10);
        return view('dispositivo.index',[ 'dispositivos' => $dispositivos ]);
    }

    public function create( Request $request ){
        $distribuidores = Distribuidor::get();
        return view('dispositivo.form',[ 'distribuidores' => $distribuidores ]);
    }
    
    public function store( Request $request ){
        $dispositivo = Input::except( 'id', '_method', '_token', 'ativo' );
        if( $request->has('ativo') )
            $dispositivo['deleted_at'] = null;
        else 
            $dispositivo['deleted_at'] = date('Y-m-d H:i:s');
        $dispositivo = Dispositivo::create( $dispositivo );
        return response()->json([ 
            'message' => 'Criado com sucesso', 
            'redirectURL' => url('/dispositivos'), 
            'dispositivo' => $dispositivo 
        ], 201 );
    }
    
    public function show( Request $request, $id ){
        try {
            return response()->json( Dispositivo::withTrashed()->findOrFail($id) );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }
    
    public function edit( Request $request, $id ){
        $distribuidores = Distribuidor::get();
        $dispositivo = Dispositivo::withTrashed()->findOrFail($id);
        return view('dispositivo.form',[ 'dispositivo' => $dispositivo, 'distribuidores' => $distribuidores ]);
    }
    
    public function update( Request $request, $id ){

        $dispositivo = Dispositivo::withTrashed()->find($id);
        $inputs = Input::except( 'id', '_method', '_token', 'ativo' );
        foreach( $inputs as $key => $value ){
            $dispositivo->$key = $value;
        }
        if( $request->has('ativo') )
            $dispositivo->deleted_at = null;
        else 
            $dispositivo->deleted_at = date('Y-m-d H:i:s');
        $dispositivo->save();
        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/dispositivos'), 
            'dispositivo' => $dispositivo 
        ], 200 );
    }
    
    public function destroy( Request $request, $id ){
        $dispositivo = Dispositivo::withTrashed()->findOrFail($id);
        $dispositivo->forceDelete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
    
}
