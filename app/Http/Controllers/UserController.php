<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use App\User;
use App\Perfil;
use App\Estado;
use App\Cidade;
use App\Empresa;
use App\Token_fcm;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    

    public function getAll( Request $request ){

        $users = User::withTrashed()->orderBy('name')->paginate(10);
        return response()->json( $users, 200 );

    }

    public function index( Request $request ){

    	$usuarios = User::withTrashed()->orderBy('name')->paginate(10);
    	return view('user.index',[ 'usuarios' => $usuarios ]);

    }

    public function create( Request $request ){

        $perfis = Perfil::All();
        $empresas = Empresa::All();

    	return view('user.form',[ 'perfis' => $perfis, 'empresas' => $empresas ]);

    }
    
    public function store( Request $request ){

        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email',
            'cpf'          => 'required|string|max:255|unique:users,cpf',
            'perfil_id'    => 'required|integer',
            'empresa_id'   => 'required|integer',
            'password'     => 'required|min:6|max:255|confirmed',

            // 'telefone'         => 'required|string|max:255',
            // 'data_nascimento'  => 'required|string|max:255',
            // 'cep'              => 'required|string|max:255',
            // 'endereco'         => 'required|string|max:255',
            // 'bairro'           => 'required|string|max:255',
            // 'cidade'           => 'required|string|max:255',
            'estado'           => 'required|string|max:255',
            // 'latitude'         => 'required|string|max:255',
            // 'longitude'        => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $inputs = Input::except('id', '_method', '_token', 'password', 'password_confirmation', 'ativo', 'imagem' );
        foreach( $inputs as $key => $value ){
            $user[$key] = $value;
        }

        $user['deleted_at'] = null;
        $user['password'] = Hash::make( $request->password );
        $user['cpf'] = Helper::onlyNumbers( $request->cpf  );

        // UPLOAD IMAGEM
        if( $request->has('imagem') ){
            $imageName = \Str::random(20). time() .'.'. request()->imagem->getClientOriginalExtension();
            request()->imagem->move( public_path('images'), $imageName );
            $user['imagem'] = $imageName;
        }

        $estado = Estado::where('uf',$user['estado'])->first();
        if( $estado ){
            $user['estado_id'] = $estado->id;
            $cidade = Cidade::where('estado_id',$estado->id)->where('nome','like','%'.$user['cidade'].'%')->first();
            if( $cidade )
                $user['cidade_id'] = $cidade->id;
        }

        $user = User::create($user);

        $token = \Str::random(60);
        if( $user->api_token )
            $token = $user->api_token;
        $user->api_token = $token;
        $user->save();

        return response()->json([ 'message' => 'Criado com sucesso', 'redirectURL' => url('/usuarios'), 'user' => $user ], 201 );

    }
    
    public function show( Request $request, $id ){
    
        try {
            
            $user = User::findOrFail($id);
            if( isset( $user->imagem ) )
                $user->imagem = url('/public/images/'.$user->imagem);

        	return response()->json( $user );
    
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }

    }
    
    public function edit( Request $request, $id ){

    	$user = User::withTrashed()->findOrFail($id);
        $perfis = Perfil::All();
        $empresas = Empresa::All();

    	return view('user.form',[ 'perfis' => $perfis, 'empresas' => $empresas, 'user' => $user ]);

    }
    
    public function update( Request $request, $id ){

        $user = User::withTrashed()->find($id);

        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,'. $id,
            'cpf'          => 'required|string|max:255|unique:users,cpf,'. $id,
            'perfil_id'    => 'required|integer',
            'empresa_id'    => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        if( $request->has('password') and strlen($request->password)>1 ){
            
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:6|max:255|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([ 'error' => $validator->messages() ], 400 );
            }
        }

        $inputs = Input::except('id', '_method', '_token', 'password', 'password_confirmation', 'ativo', 'imagem' );

        if( $request->has('ativo') )
        	$user->deleted_at = null;
        else 
        	$user->deleted_at = date('Y-m-d H:i:s');

        foreach( $inputs as $key => $value ){
            $user->$key = $value;
        }

        if( $request->has('password') and strlen( $request->password )>1 )
            $user->password = Hash::make( $request->password );

        $user->cpf = Helper::onlyNumbers( $request->cpf  );

        // UPLOAD IMAGEM
        if( $request->has('imagem') ){
            $imageName = \Str::random(20). time() .'.'. request()->imagem->getClientOriginalExtension();
            request()->imagem->move( public_path('images'), $imageName );
            $user->imagem = $imageName;
        }

        $estado = Estado::where('uf',$user->estado)->first();
        if( $estado ){
            $user->estado_id = $estado->id;
            $cidade = Cidade::where('estado_id',$estado->id)->where('nome','like','%'.$user->cidade.'%')->first();
            if( $cidade )
                $user->cidade_id = $cidade->id;
        }

        $token = \Str::random(60);
        if( $user->api_token )
            $token = $user->api_token;
        $user->api_token = $token;

        $user->save();

        return response()->json([ 'message' => 'Atualizado com sucesso', 'redirectURL' => url('/usuarios'), 'user' => $user ], 200 );

    }
    
    public function destroy( Request $request, $id ){

    	$user = User::findOrFail($id);
        $user->delete();

        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }

    public function perfil( Request $request ){

        return Self::edit( $request, \Auth::user()->id );

    }


    public function login( Request $request ){


        $credentials = request(['email', 'password']);

        $token = '';

        $user = User::where([ 'email' => $credentials['email']])->first();
        if( $user ){
            if( password_verify($credentials['password'], $user->password) ){

                $token = \Str::random(60);
                if( $user->api_token )
                    $token = $user->api_token;

            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        if( request('device_token') != '' && request('fcm_token') != '' ){

            Token_fcm::where( 'device_token', '=', request('device_token') )->update([ 'device_token' => null ]);

            Token_fcm::create([
                'user_id'  => $user->id,
                'fcm_token'  => request('fcm_token'),
                'device_token' => request('device_token'),
            ]);
        }

        if( $token ){
            $user->api_token = $token;
            $user->save();
        }

        if( isset( $user->imagem ) )
            $user->imagem = url('/public/images/'.$user->imagem);

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'   => $user,
        ]);

    }

    public function logout( Request $request ){

        $ation = Token_fcm::where('user_id', \Auth::user()->id);
        if( $request->has('device_token') ){
            $ation->where(function($query) use ($request){
                $query->whereNull('device_token')
                      ->orWhere('device_token',$request->device_token);
            });
        }
        $ation->delete();

        return response()->json(['message'=>'Logout feito com sucesso']);

    }

    public function resetpassword( Request $request ){
        $validator = Validator::make($request->all(),['email'=>'required|email|max:255']);
        if( $validator->fails() ) 
            return response()->json(['error'=>$validator->messages()],400);
        $user = User::where(['email'=>$request->email])->first();
        if( $user ){
            $status = Password::sendResetLink($request->only('email'));
            return $status === Password::RESET_LINK_SENT
                                ? response()->json(['message'=>'Link de redefinição de senha enviado para seu e-mail'],200)
                                : response()->json(['message'=>'Não é possível enviar link de redefinição'],400);
        }
        return response()->json(['error'=>'E-mail não encontrado'],400);
    }

}
