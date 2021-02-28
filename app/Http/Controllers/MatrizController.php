<?php

namespace App\Http\Controllers;

use App\Matriz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class MatrizController extends Controller
{

    public function getAll( Request $request ){
        $matrizes = Matriz::orderBy('bilhete')->paginate(10);
        return response()->json( $matrizes, 200 );
    }

    public function index( Request $request ){
        $matrizes = Matriz::orderBy('bilhete')->paginate(10);
        return view('matriz.index',[ 'matrizes' => $matrizes ]);
    }

    public function create( Request $request ){
        return view('matriz.form');
    }
    
    public function store( Request $request ){
        $matriz = Matriz::create( Input::except( 'id', '_method', '_token' ) );
        return response()->json([ 
            'message' => 'Criado com sucesso', 
            'redirectURL' => url('/matrizes'), 
            'matriz' => $matriz 
        ], 201 );
    }
    
    public function show( Request $request, $id ){
        try {
            return response()->json( Matriz::findOrFail($id) );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }
    
    public function edit( Request $request, $id ){
        $matriz = Matriz::findOrFail($id);
        return view('matriz.form',[ 'matriz' => $matriz ]);
    }
    
    public function update( Request $request, $id ){

        $matriz = Matriz::find($id);
        $inputs = Input::except( 'id', '_method', '_token' );
        foreach( $inputs as $key => $value ){
            $matriz->$key = $value;
        }
        $matriz->save();
        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/matrizes'), 
            'matriz' => $matriz 
        ], 200 );
    }
    
    public function destroy( Request $request, $id ){
        $matriz = Matriz::findOrFail($id);
        $matriz->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
    
}
