<?php

namespace App\Http\Controllers;

use App\Estado;
use App\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class EstadoController extends Controller
{

    public function getAll( Request $request ){
        $estados = Estado::orderBy('nome')->paginate(10);
        return response()->json( $estados, 200 );
    }

    public function get( Request $request ){
        $estados = Estado::orderBy('nome')->get();
        if( $request->has('term') ){
            $estados = Estado::select('estados.id')
                        ->selectRaw('CONCAT(estados.nome," - ",estados.uf) AS name')
                        ->where('estados.nome','like','%'.$request->term.'%')
                        ->orWhere('estados.uf','like','%'.$request->term.'%')
                        ->orderBy('estados.nome')
                        ->get();
        }
        return response()->json( $estados, 200 );
    }

    public function index( Request $request ){
        $estados = Estado::orderBy('nome')->paginate(10);
        return view('estado.index',[ 'estados' => $estados ]);
    }

    public function create( Request $request ){
        return view('estado.form');
    }
    
    public function store( Request $request ){
        $estado = Estado::create( Input::except( 'id', '_method', '_token' ) );
        return response()->json([ 
            'message' => 'Criado com sucesso', 
            'redirectURL' => url('/estados'), 
            'estado' => $estado 
        ], 201 );
    }
    
    public function show( Request $request, $id ){
        try {
            return response()->json( Estado::findOrFail($id) );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }
    
    public function edit( Request $request, $id ){
        $estado = Estado::findOrFail($id);
        return view('estado.form',[ 'estado' => $estado ]);
    }
    
    public function update( Request $request, $id ){
        $estado = Estado::find($id);
        $inputs = Input::except( 'id', '_method', '_token' );
        foreach( $inputs as $key => $value ){
            $estado->$key = $value;
        }
        $estado->save();
        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/estados'), 
            'estado' => $estado 
        ], 200 );
    }
    
    public function destroy( Request $request, $id ){
        $estado = Estado::findOrFail($id);
        $estado->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
    
    public function cidades( Request $request, $id ){
        $estado = Estado::findOrFail($id);
        $cidades = Cidade::where('estado_id',$id)->orderBy('nome');
        if( $request->has('term') ){
            $cidades = $cidades->where('nome','like','%'.$request->term.'%');
        }
        $cidades = $cidades->get();
        return response()->json( $cidades, 200 );
    }
    
}
