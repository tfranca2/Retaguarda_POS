<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Perfil;
use App\Permissions;
use Session;

class PerfilController extends Controller
{
    
    public function getAll( Request $request ){

        $perfis = Perfil::orderBy('nome')->paginate(10);
        return response()->json( $perfis, 200 );

    }

    public function index( Request $request ){

    	$perfis = Perfil::orderBy('nome')->paginate(10);
    	return view('perfil.index',[ 'perfis' => $perfis ]);

    }

    public function create( Request $request ){

    	return view('perfil.form');

    }
    
    public function store( Request $request ){

    	$perfil['nome'] = $request->nome;
    	if( !$request->has('ativo') )
    		$perfil['deleted_at'] = date('Y-m-d H:i:s');

    	$perfil = Perfil::create($perfil);

        if( $request->permissao ){
            foreach( $request->permissao as $role => $value ){
                Permissions::create([
                    'role' => $role,
                    'perfil_id' => $perfil->id
                ]);
            }
        }

    	return response()->json([ 'message' => 'Criado com sucesso', 'redirectURL' => url('/perfis'), 'perfil' => $perfil ], 201 );

    }
    
    public function show( Request $request, $id ){
        
        try {
    
        	return response()->json( Perfil::findOrFail($id) );

        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }

    }
    
    public function edit( Request $request, $id ){

    	$perfil = Perfil::findOrFail($id);
    	return view('perfil.form',[ 'perfil' => $perfil ]);

    }
    
    public function update( Request $request, $id ){

        $perfil = Perfil::find($id);
        $perfil->nome = $request->nome;

        if( $request->has('ativo') )
        	$perfil->deleted_at = null;
        else 
        	$perfil->deleted_at = date('Y-m-d H:i:s');

        $perfil->save();

        Permissions::where([ 'perfil_id' => $perfil->id ])->delete();
        if( $request->permissao ){
            foreach( $request->permissao as $role => $value ){
                Permissions::create([
                    'role' => $role,
                    'perfil_id' => $perfil->id
                ]);
            }
        }

    	return response()->json([ 'message' => 'Atualizado com sucesso', 'redirectURL' => url('/perfis'), 'perfil' => $perfil ], 200 );

    }
    
    public function destroy( Request $request, $id ){

    	$perfil = Perfil::findOrFail($id);
        $perfil->delete();

    	return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }

}
