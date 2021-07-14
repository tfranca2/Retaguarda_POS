<?php

namespace App\Http\Controllers;

use App\User;
use Helper;
use App\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DispositivoController extends Controller
{

    public function getAll( Request $request ){
        if( Helper::temPermissao('dispositivos-gerenciar') )
            $dispositivos = Dispositivo::withTrashed()->orderBy('nome')->paginate(10);
        else
            $dispositivos = Dispositivo::withTrashed()->where('distribuidor_id',\Auth::user()->id)->orderBy('nome')->paginate(10);
        return response()->json( $dispositivos, 200 );
    }

    public function index( Request $request ){
        if( Helper::temPermissao('dispositivos-gerenciar') )
            $dispositivos = Dispositivo::withTrashed()->orderBy('nome')->paginate(10);
        else
            $dispositivos = Dispositivo::withTrashed()->where('distribuidor_id',\Auth::user()->id)->orderBy('nome')->paginate(10);
        return view('dispositivo.index',[ 'dispositivos' => $dispositivos ]);
    }

    public function create( Request $request ){
        $idPerfilAdmin = \DB::table('permissions')->where('role','vendas-gerenciar')->pluck('perfil_id')->toArray();
        $usuarios = User::whereNotIn('perfil_id',$idPerfilAdmin)->get();
        return view('dispositivo.form',[ 'usuarios' => $usuarios ]);
    }
    
    public function store( Request $request ){
        $dispositivo = Input::except( 'id', '_method', '_token', 'ativo' );
        if( $request->has('ativo') )
            $dispositivo['deleted_at'] = null;
        else 
            $dispositivo['deleted_at'] = date('Y-m-d H:i:s');
        $dispositivo['mac'] = strtoupper($dispositivo['mac']);
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
        $idPerfilAdmin = \DB::table('permissions')->where('role','vendas-gerenciar')->pluck('perfil_id')->toArray();
        $usuarios = User::whereNotIn('perfil_id',$idPerfilAdmin)->get();
        $dispositivo = Dispositivo::withTrashed()->findOrFail($id);
        return view('dispositivo.form',[ 'dispositivo' => $dispositivo, 'usuarios' => $usuarios ]);
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
        $dispositivo->mac = strtoupper($dispositivo->mac);
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

    public function toggleActive( Request $request, $id ){
        $dispositivo = Dispositivo::withTrashed()->findOrFail($id);
        $dispositivo->deleted_at = (($dispositivo->deleted_at )?null:date('Y-m-d H:i:s'));
        $dispositivo->save();
        return response()->json([ 'message' => 'Alterado com sucesso!' ], 200 );
    }
    
}
