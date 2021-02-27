<?php

namespace App\Http\Controllers;

use App\Faq;
use Session;
use Validator;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class FaqController extends Controller
{

    public function getAll( Request $request ){
        $faqs = Faq::orderBy('titulo')->paginate(10);
        return response()->json($faqs,200);
    }

    public function index( Request $request ){
    	$faqs = Faq::withTrashed()->orderBy('titulo')->paginate(10);
    	return view('faq.index',['faqs'=>$faqs]);
    }

    public function indexApp( Request $request ){
        $faqs = Faq::withTrashed()->orderBy('titulo');
        if( $request->has('q') ){
            $faqs = $faqs->where('titulo', 'like', '%'.$request->q.'%')
                        ->orWhere('descricao', 'like', '%'.$request->q.'%');
        }
        $faqs = $faqs->paginate(10);
        return view( 'faq.app', [
            'faqs'=>$faqs,
            'q' => $request->q,
        ]);
    }

    public function create( Request $request ){
    	return view('faq.form');
    }
    
    public function store( Request $request ){

        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
        ]);
        if( $validator->fails() )
            return response()->json(['error'=>$validator->messages()],400);

        $inputs = Input::except('id', '_method', '_token', 'ativo');
        foreach( $inputs as $key => $value ){
            $faq[$key] = $value;
        }

        if( $request->has('ativo') )
            $faq['deleted_at'] = null;
        else 
            $faq['deleted_at'] = date('Y-m-d H:i:s');

        $faq = Faq::create($faq);

        return response()->json([ 
            'message' => 'Criado com sucesso', 
            'redirectURL' => url('/faq'), 
            'faq' => $faq, 
        ],201);

    }
    
    public function show( Request $request, $id ){
        try {
        	return response()->json(Faq::withTrashed()->findOrFail($id));
        } catch( \Exception $e ){
            return response()->json(['error'=>$e->getMessage()],404);
        }
    }
    
    public function edit( Request $request, $id ){
    	$faq = Faq::withTrashed()->findOrFail($id);
    	return view('faq.form',['faq'=>$faq]);
    }
    
    public function update( Request $request, $id ){

        $faq = Faq::withTrashed()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
        ]);
        if( $validator->fails() )
            return response()->json(['error'=>$validator->messages()],400);

        $inputs = Input::except('id', '_method', '_token', 'ativo');

        if( $request->has('ativo') )
        	$faq->deleted_at = null;
        else 
        	$faq->deleted_at = date('Y-m-d H:i:s');

        foreach( $inputs as $key => $value ){
            $faq->$key = $value;
        }

        $faq->save();

        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/faq'), 
            'faq' => $faq, 
        ],200);
    }
    
    public function destroy( Request $request, $id ){
    	$faq = Faq::withTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['message'=>'Deletado com sucesso'],204);
    }

}
