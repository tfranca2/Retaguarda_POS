<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Perfil;
use App\Empresa;
// use App\Cliente;
use Session;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;
use App\Token_fcm;
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
            // 'estado'           => 'required|string|max:255',
            // 'latitude'         => 'required|string|max:255',
            // 'longitude'        => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $inputs = Input::except('id', '_method', '_token', 'password', 'password_confirmation', 'ativo', 'telefone', 'data_nascimento', 'cep', 'endereco', 'numero', 'bairro', 'complemento', 'cidade', 'estado', 'latitude', 'longitude' );
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

        $user = User::create($user);

        if( $request->perfil_id == 2 ){

            
            $empresa = Empresa::find($request->empresa_id);
            $geocode = json_decode( file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?key=". $empresa->google_maps_api_key ."&address=". urlencode( $request->endereco .', '. $request->bairro .', '. $request->numero .', '. $request->cidade .', '. $request->estado ) ) );
            $request->latitude = $geocode->results[0]->geometry->location->lat;
            $request->longitude = $geocode->results[0]->geometry->location->lng;

            // $cliente['nome'] = $request->name;
            // $cliente['email'] = $request->email;
            // $cliente['cpf'] = $request->cpf;
            // $cliente['telefone'] = $request->telefone;
            // $cliente['data_nascimento'] = $request->data_nascimento;
            // $cliente['cep'] = $request->cep;
            // $cliente['endereco'] = $request->endereco;

            // if( $request->has('numero') )
            //     $cliente['numero'] = $request->numero;
            // if( $request->has('complemento') )
            //     $cliente['complemento'] = $request->complemento;
            // if( $request->has('bairro') )
            //     $cliente['bairro'] = $request->bairro;

            // $cliente['cidade'] = $request->cidade;
            // $cliente['estado'] = $request->estado;
            // $cliente['latitude'] = $request->latitude;
            // $cliente['longitude'] = $request->longitude;

            // if( $request->has('condominio') )
            //     $cliente['condominio'] = $request->condominio;
            // if( $request->has('unidade') )
            //     $cliente['unidade'] = $request->unidade;
            // if( $request->has('bloco') )
            //     $cliente['bloco'] = $request->bloco;
            

            // $cliente['usuario_id'] = $user->id;
            // if( $request->has('imagem') )
            //     $cliente['imagem'] = $user->imagem;

            // $cliente = Cliente::create($cliente);

        }

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

        $inputs = Input::except('id', '_method', '_token', 'password', 'password_confirmation', 'ativo', 'imagem');

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

        // $user->cliente = Cliente::where('usuario_id',$user->id)->first();

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
