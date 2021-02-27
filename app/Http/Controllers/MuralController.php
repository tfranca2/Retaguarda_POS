<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mural;
use App\Cliente;
use App\Prestador;
use Session;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;
use Illuminate\Validation\Rule;

class MuralController extends Controller
{
    

    public function getAll( Request $request ){

        $mural = Mural::orderBy('created_at', 'desc')->paginate(10);
        
        foreach( $mural as $anuncio ){
            $fotos = [];
            foreach( json_decode($anuncio->fotos) as $foto ){
                $fotos[] = url('/public/images/'.$foto);
            }
            if( $fotos )
                $anuncio->fotos = $fotos;
        }

        return response()->json( $mural );

    }

    public function index( Request $request ){

    	$mural = Mural::orderBy('created_at', 'desc')->paginate(10);
    	return view('mural.index',[ 'mural' => $mural ]);

    }

    public function create( Request $request ){

    	return view('mural.form');

    }
    
    public function store( Request $request ){

        $validator = Validator::make($request->all(), [
            'tipo' => [
                'required',
                Rule::in(['produto', 'servico']),
            ],
            'titulo' => 'required|string|max:255',
            'descricao' => 'required',
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:255',
            'foto' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $inputs = Input::except('id', '_method', '_token', 'ativo', 'foto');
        foreach( $inputs as $key => $value ){
            $mural[$key] = $value;
        }

        $mural['user_id'] = \Auth::user()->id;

        // UPLOAD IMAGEM
        $fotos = [];
        if( $request->has('foto') ){
            $fts = $request->file('foto');
            if( $fts ){
                foreach( $fts as $key => $foto ){
                    $imageName = \Str::random(20).time().'.'.$foto->getClientOriginalExtension();
                    $foto->move( public_path('images'), $imageName );
                    $fotos[] = $imageName;
                }
            } else {
                foreach( $request->foto as $key => $foto ){
                    if( preg_match('/^data:image\/(\w+);base64,/', $foto, $type) ){
                        $data = substr($foto, strpos($foto,',') + 1);
                        $type = strtolower($type[1]);

                        if( !in_array($type,[ 'jpg', 'jpeg', 'png' ]) )
                            return response()->json(['error'=>'Tipo de imagem(.'.$type.') não permitido.'],403);

                        $data = base64_decode($data);
                        if( $data === false )
                            return response()->json(['error'=>'base64_decode failed'],403);

                        $filename = \Str::random(20).time().'.'.$type;
                        $urlImage =  public_path('images').'/'.$filename;
                        file_put_contents( $urlImage, $data );

                        $fotos[] = $filename;
                    }
                }
            }
        }

        if( $fotos )
            $mural['fotos'] = json_encode( $fotos );

        $mural = Mural::create($mural);

        $fotos = [];
        foreach( json_decode($mural->fotos) as $foto ){
            $fotos[] = url('/public/images/'.$foto);
        }
        if( $fotos )
            $mural->fotos = ($fotos);

        return response()->json([ 'message' => 'Criado com sucesso', 'redirectURL' => url('/mural'), 'mural' => $mural ], 201 );

    }
    
    public function show( Request $request, $id ){

        try {
    
            $mural = Mural::findOrFail($id);

            $usuario = $mural->usuario()->first();
            if( $usuario )
                $mural->usuario = $usuario;

            if( isset($mural->usuario) ) {
                if( $mural->usuario->imagem )
                    $mural->usuario->imagem = url('/public/images/'.$mural->usuario->imagem);

                if( $mural->usuario->perfil_id == 3 ){
                    // usuario prestador
                    $prestador = Prestador::where('usuario_id',$mural->usuario->id)->first();
                    if( $prestador ){
                        $mural->usuario->detalhes = $prestador;
                        $mural->usuario->detalhes->tipo = 'prestador';
                    }
                } elseif( $mural->usuario->perfil_id == 2 ){
                    // usuario cliente
                    $cliente = Cliente::where('usuario_id',$mural->usuario->id)->first();
                    if( $cliente ){
                        $mural->usuario->detalhes = $cliente;
                        $mural->usuario->detalhes->tipo = 'cliente';
                    }
                }
            } 


            if( $mural->fotos ){
                $fotos = [];
                foreach( json_decode($mural->fotos) as $foto ){
                    $fotos[] = url('/public/images/'.$foto);
                }
                if( $fotos )
                    $mural->fotos = ($fotos);
            }

        	return response()->json( $mural );
    
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }

    }
    
    public function edit( Request $request, $id ){

    	$mural = Mural::findOrFail($id);
    	return view('mural.form',[ 'mural' => $mural ]);

    }
    
    public function update( Request $request, $id ){

        $mural = Mural::findOrFail($id);

        if( Helper::temPermissao('mural-editar') || Helper::temPermissao('mural-gerenciar') ){
            
            if( !Helper::temPermissao('mural-gerenciar') and $mural->user_id != \Auth::user()->id )
                return response()->json([ 'error' => 'Seu usuário não tem permissão para editar este anúncio do mural' ], 401 );


        $inputs = Input::except('id', '_method', '_token', 'foto');
        foreach( $inputs as $key => $value ){
            $mural->$key = $value;
        }

        // UPLOAD IMAGEM
        $fotos = [];
        if( $request->has('foto') ){
            $fts = $request->file('foto');
            if( $fts ){
                foreach( $fts as $key => $foto ){
                    $imageName = \Str::random(20).time().'.'.$foto->getClientOriginalExtension();
                    $foto->move( public_path('images'), $imageName );
                    $fotos[] = $imageName;
                }
            } else {
                foreach( $request->foto as $key => $foto ){
                    if( preg_match('/^data:image\/(\w+);base64,/', $foto, $type) ){
                        $data = substr($foto, strpos($foto,',') + 1);
                        $type = strtolower($type[1]);

                        if( !in_array($type,[ 'jpg', 'jpeg', 'png' ]) )
                            return response()->json(['error'=>'Tipo de imagem(.'.$type.') não permitido.'],403);

                        $data = base64_decode($data);
                        if( $data === false )
                            return response()->json(['error'=>'base64_decode failed'],403);

                        $filename = \Str::random(20).time().'.'.$type;
                        $urlImage =  public_path('images').'/'.$filename;
                        file_put_contents( $urlImage, $data );

                        $fotos[] = $filename;
                    }
                }
            }
        }

        if( $fotos )
            $mural->fotos = json_encode( $fotos );

        $mural->save();

        $fotos = [];
        foreach( json_decode($mural->fotos) as $foto ){
            $fotos[] = url('/public/images/'.$foto);
        }
        if( $fotos )
            $mural->fotos = ($fotos);

        return response()->json([ 'message' => 'Atualizado com sucesso', 'redirectURL' => url('/mural'), 'mural' => $mural ], 200 );

        }

        return response()->json([ 'error' => 'Seu usuário não tem permissão para editar anúncios do mural' ], 401 );

    }
    
    public function destroy( Request $request, $id ){

    	$mural = Mural::findOrFail($id);
        if( Helper::temPermissao('mural-excluir') || Helper::temPermissao('mural-gerenciar') ){

            if( !Helper::temPermissao('mural-gerenciar') and $mural->user_id != \Auth::user()->id )
                return response()->json([ 'error' => 'Seu usuário não tem permissão para apagar este anúncio do mural' ], 401 );

            $mural->delete();
            return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
        }

        return response()->json([ 'error' => 'Seu usuário não tem permissão para apagar anúncios do mural' ], 401 );

    }

}
