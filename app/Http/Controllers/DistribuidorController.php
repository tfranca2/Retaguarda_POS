<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Distribuidor;
use Session;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;

class DistribuidorController extends Controller
{
    

    public function getAll( Request $request ){

        $distribuidores = Distribuidor::orderBy('nome')->paginate(10);
        return response()->json( $distribuidores, 200 );

    }

    public function index( Request $request ){

    	$distribuidores = Distribuidor::orderBy('nome')->paginate(10);
    	return view('distribuidor.index',[ 'distribuidores' => $distribuidores ]);

    }

    public function create( Request $request ){

    	return view('distribuidor.form');

    }
    
    public function store( Request $request ){

        if( $request->has('email') ){
            $distribuidor = Distribuidor::where('email',$request->email)->first();
            if( $distribuidor )
                return Self::update( $request, $distribuidor->id );
        }

        $validator = Validator::make($request->all(), [
            'nome'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:distribuidor,email',
            // 'cpf'          => 'required|string|max:255|unique:distribuidor,cpf',
            'latitude'     => 'required|string|max:255',
            'longitude'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $inputs = Input::except('id', '_method', '_token', 'ativo');
        foreach( $inputs as $key => $value ){
            $distribuidor[$key] = $value;
        }

        if( $request->has('ativo') )
            $distribuidor['deleted_at'] = null;
        else 
            $distribuidor['deleted_at'] = date('Y-m-d H:i:s');

        $distribuidor['cpf'] = Helper::onlyNumbers( $request->cpf  );

        // UPLOAD IMAGEM
        if( $request->has('imagem') ){
            $imageName = \Str::random(20). time() .'.'. request()->imagem->getClientOriginalExtension();
            request()->imagem->move( public_path('images'), $imageName );
            $distribuidor['imagem'] = $imageName;
        }

        $distribuidor = Distribuidor::create($distribuidor);

        return response()->json([ 'message' => 'Criado com sucesso', 'redirectURL' => url('/distribuidores'), 'distribuidor' => $distribuidor ], 201 );

    }
    
    public function show( Request $request, $id ){
    
        try {
    
        	return response()->json( Distribuidor::findOrFail($id) );
    
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }

    }
    
    public function edit( Request $request, $id ){

    	$distribuidor = Distribuidor::findOrFail($id);
    	return view('distribuidor.form',[ 'distribuidor' => $distribuidor ]);

    }
    
    public function update( Request $request, $id ){

        $distribuidor = Distribuidor::find($id);

        $validator = Validator::make($request->all(), [
            'nome'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:distribuidor,email,'. $id,
            // 'cpf'          => 'required|string|max:255|unique:distribuidor,cpf,'. $id,
            'latitude'     => 'required|string|max:255',
            'longitude'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $inputs = Input::except('id', '_method', '_token', 'ativo', 'imagem');

        if( $request->has('ativo') )
        	$distribuidor->deleted_at = null;
        else 
        	$distribuidor->deleted_at = date('Y-m-d H:i:s');

        foreach( $inputs as $key => $value ){
            $distribuidor->$key = $value;
        }

        if( $request->has('password') and strlen( $request->password )>1 )
            $distribuidor->password = Hash::make( $request->password );

        $distribuidor->cpf = Helper::onlyNumbers( $request->cpf  );

        // UPLOAD IMAGEM
        if( $request->has('imagem') ){
            $imageName = \Str::random(20). time() .'.'. request()->imagem->getClientOriginalExtension();
            request()->imagem->move( public_path('images'), $imageName );
            $distribuidor->imagem = $imageName;
        }

        $distribuidor->save();

        return response()->json([ 'message' => 'Atualizado com sucesso', 'redirectURL' => url('/distribuidores'), 'distribuidor' => $distribuidor ], 200 );

    }
    
    public function destroy( Request $request, $id ){

    	$distribuidor = Distribuidor::findOrFail($id);
        $distribuidor->delete();

        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }

}
