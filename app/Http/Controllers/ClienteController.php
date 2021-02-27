<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use Session;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;

class ClienteController extends Controller
{
    

    public function getAll( Request $request ){

        $clientes = Cliente::orderBy('nome')->paginate(10);
        return response()->json( $clientes, 200 );

    }

    public function index( Request $request ){

    	$clientes = Cliente::orderBy('nome')->paginate(10);
    	return view('cliente.index',[ 'clientes' => $clientes ]);

    }

    public function create( Request $request ){

    	return view('cliente.form');

    }
    
    public function store( Request $request ){

        if( $request->has('email') ){
            $cliente = Cliente::where('email',$request->email)->first();
            if( $cliente )
                return Self::update( $request, $cliente->id );
        }

        $validator = Validator::make($request->all(), [
            'nome'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:cliente,email',
            // 'cpf'          => 'required|string|max:255|unique:cliente,cpf',
            'latitude'     => 'required|string|max:255',
            'longitude'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $inputs = Input::except('id', '_method', '_token', 'ativo');
        foreach( $inputs as $key => $value ){
            $cliente[$key] = $value;
        }

        if( $request->has('ativo') )
            $cliente['deleted_at'] = null;
        else 
            $cliente['deleted_at'] = date('Y-m-d H:i:s');

        $cliente['cpf'] = Helper::onlyNumbers( $request->cpf  );

        // UPLOAD IMAGEM
        if( $request->has('imagem') ){
            $imageName = \Str::random(20). time() .'.'. request()->imagem->getClientOriginalExtension();
            request()->imagem->move( public_path('images'), $imageName );
            $cliente['imagem'] = $imageName;
        }

        $cliente = Cliente::create($cliente);

        return response()->json([ 'message' => 'Criado com sucesso', 'redirectURL' => url('/clientes'), 'cliente' => $cliente ], 201 );

    }
    
    public function show( Request $request, $id ){
    
        try {
    
        	return response()->json( Cliente::findOrFail($id) );
    
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }

    }
    
    public function edit( Request $request, $id ){

    	$cliente = Cliente::findOrFail($id);
    	return view('cliente.form',[ 'cliente' => $cliente ]);

    }
    
    public function update( Request $request, $id ){

        $cliente = Cliente::find($id);

        $validator = Validator::make($request->all(), [
            'nome'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:cliente,email,'. $id,
            // 'cpf'          => 'required|string|max:255|unique:cliente,cpf,'. $id,
            'latitude'     => 'required|string|max:255',
            'longitude'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $inputs = Input::except('id', '_method', '_token', 'ativo', 'imagem');

        if( $request->has('ativo') )
        	$cliente->deleted_at = null;
        else 
        	$cliente->deleted_at = date('Y-m-d H:i:s');

        foreach( $inputs as $key => $value ){
            $cliente->$key = $value;
        }

        if( $request->has('password') and strlen( $request->password )>1 )
            $cliente->password = Hash::make( $request->password );

        $cliente->cpf = Helper::onlyNumbers( $request->cpf  );

        // UPLOAD IMAGEM
        if( $request->has('imagem') ){
            $imageName = \Str::random(20). time() .'.'. request()->imagem->getClientOriginalExtension();
            request()->imagem->move( public_path('images'), $imageName );
            $cliente->imagem = $imageName;
        }

        $cliente->save();

        return response()->json([ 'message' => 'Atualizado com sucesso', 'redirectURL' => url('/clientes'), 'cliente' => $cliente ], 200 );

    }
    
    public function destroy( Request $request, $id ){

    	$cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }

}
