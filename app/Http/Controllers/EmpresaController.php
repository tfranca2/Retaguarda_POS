<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use Session;
use Validator;
use App\Helpers\Helper;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Servico;

class EmpresaController extends Controller
{
    
    public function getAll( Request $request ){

        $empresas = Empresa::paginate(10);
        return response()->json( $empresas, 200 );

    }

    public function index( Request $request ){

    	$empresas = Empresa::paginate(10);
    	return view('empresa.index',[ 'empresas' => $empresas ]);

    }

    public function create( Request $request ){

        $servicos = Servico::All();
    	return view('empresa.form',['servicos'=>$servicos]);

    }
    
    public function store( Request $request ){

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|max:255|unique:empresa,cnpj',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $empresa['nome'] = $request->nome;

        if( $request->has('ativo') )
            $empresa['deleted_at'] = null;
        else 
            $empresa['deleted_at'] = date('Y-m-d H:i:s');

        $empresa['cnpj'] = Helper::onlyNumbers( $request->cnpj  );

        // UPLOAD LOGOS
        if( $request->has('main_logo') ){
            $imageName = \Str::random(20). time() .'.'. request()->main_logo->getClientOriginalExtension();
            request()->main_logo->move( public_path('images'), $imageName );
            $empresa['main_logo'] = $imageName;
        }

        if( $request->has('favicon') ){
            $imageName = \Str::random(20). time() .'.'. request()->favicon->getClientOriginalExtension();
            request()->favicon->move( public_path('images'), $imageName );
            $empresa['favicon'] = $imageName;
        } 

        if( $request->has('menu_logo') ){
            $imageName = \Str::random(20). time() .'.'. request()->menu_logo->getClientOriginalExtension();
            request()->menu_logo->move( public_path('images'), $imageName );
            $empresa['menu_logo'] = $imageName;
        } 

        if( $request->has('contracted_menu_logo') ){
            $imageName = \Str::random(20). time() .'.'. request()->contracted_menu_logo->getClientOriginalExtension();
            request()->contracted_menu_logo->move( public_path('images'), $imageName );
            $empresa['contracted_menu_logo'] = $imageName;
        } 

        $empresa = Empresa::create($empresa);

    	return response()->json([ 'message' => 'Criado com sucesso', 'redirectURL' => url('/empresas'), 'empresa' => $empresa ], 201 );

    }
    
    public function show( Request $request, $id ){

        try {

    	   return response()->json( Empresa::findOrFail($id) );

        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }

    }
    
    public function edit( Request $request, $id ){

        $empresa = Empresa::findOrFail($id);
        $servicos = Servico::All();

    	return view('empresa.form',[ 'empresa' => $empresa, 'servicos' => $servicos ]);

    }
    
    public function update( Request $request, $id ){

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|max:255|unique:empresa,cnpj,'. $id,
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $empresa = Empresa::find($id);

        $inputs = Input::except('id', '_method', '_token', 'ativo', 'main_logo', 'favicon', 'menu_logo', 'contracted_menu_logo', 'servico_id', 'diasemana', 'inicio', 'fim', 'desconto' );
        foreach( $inputs as $key => $value ){
            $empresa->$key = $value;
        }

        if( $request->has('ativo') )
        	$empresa->deleted_at = null;
        else 
        	$empresa->deleted_at = date('Y-m-d H:i:s');

        $empresa->cnpj = Helper::onlyNumbers( $request->cnpj  );

        if( $request->has('servico_id') ){
            foreach( $request->servico_id as $key => $servico ){
                $desconto[] = array( 
                    'servico_id' => $servico, 
                    'diasemana' => $request->diasemana[$key], 
                    'inicio' => $request->inicio[$key], 
                    'fim' => $request->fim[$key], 
                    'desconto' => $request->desconto[$key], 
                );
            }
            $empresa->descontos = json_encode( $desconto );
        }

        // UPLOAD LOGOS
        if( $request->has('main_logo') ){
            $imageName = \Str::random(20). time() .'.'. request()->main_logo->getClientOriginalExtension();
            request()->main_logo->move( public_path('images'), $imageName );
            $empresa->main_logo = $imageName;
        }

        if( $request->has('favicon') ){
            $imageName = \Str::random(20). time() .'.'. request()->favicon->getClientOriginalExtension();
            request()->favicon->move( public_path('images'), $imageName );
            $empresa->favicon = $imageName;
        } 

        if( $request->has('menu_logo') ){
            $imageName = \Str::random(20). time() .'.'. request()->menu_logo->getClientOriginalExtension();
            request()->menu_logo->move( public_path('images'), $imageName );
            $empresa->menu_logo = $imageName;
        } 

        if( $request->has('contracted_menu_logo') ){
            $imageName = \Str::random(20). time() .'.'. request()->contracted_menu_logo->getClientOriginalExtension();
            request()->contracted_menu_logo->move( public_path('images'), $imageName );
            $empresa->contracted_menu_logo = $imageName;
        } 

        $empresa->save();

    	return response()->json([ 'message' => 'Atualizado com sucesso', 'redirectURL' => url('/empresas'), 'empresa' => $empresa ], 200 );

    }
    
    public function destroy( Request $request, $id ){

    	$empresa = Empresa::findOrFail($id);
        $empresa->delete();

    	return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }    

    public function configuracoes( Request $request ){

        return Self::edit( $request, Auth::user()->empresa_id );

    }

}
