<?php

namespace App\Http\Controllers;

use Helper;
use App\Venda;
use App\Etapa;
use Validator;
use App\Matriz;
use App\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class VendaController extends Controller
{

    public function getAll( Request $request ){
        $vendas = Venda::orderBy('created_at','DESC')->paginate(10);
        return response()->json( $vendas, 200 );
    }

    public function index( Request $request ){
        $vendas = Venda::orderBy('created_at','DESC')->paginate(10);
        return view('venda.index',[ 'vendas' => $vendas ]);
    }

    public function create( Request $request ){
        $dispositivos = Dispositivo::get();
        $etapa = Etapa::ativa();
        $showQuantidade = 0;
        if( in_array( $etapa->tipo, [ 2, 4 ] ) )
            $showQuantidade = 2;
        if( in_array( $etapa->tipo, [ 3, 5 ] ) )
            $showQuantidade = 3;
        return view('venda.form',[ 'dispositivos' => $dispositivos, 'etapa_id' => $etapa->id, 'showQuantidade' => $showQuantidade ]);
    }
    
    public function store( Request $request ){

        $validators = [
            'etapa_id' => 'required|integer',
            'dispositivo_id' => 'required_if:mac,""|integer',
            'mac' => 'required_if:dispositivo_id,""|max:255',
            // 'nome' => 'required|max:255',
            'cpf' => 'required_if:telefone,""|max:255',
            'telefone' => 'required_if:cpf,""|max:255',
        ];

        $etapa = Etapa::ativa();
        if( in_array( $etapa->tipo, [ 4, 5 ] ) )
            $validators['quantidade'] = 'required|integer|between:1,3';

        if( $etapa->tipo == 4 ) // simples e dupla
            $validators['quantidade'] = 'required|integer|between:1,2';
        
        $validator = Validator::make($request->all(),$validators);
        if( $validator->fails() )
            return response()->json(['error'=>$validator->messages()],400);

        if( $request->has('dispositivo_id') )
            $dispositivo = Dispositivo::find($request->dispositivo_id);
        else
            $dispositivo = Dispositivo::where('distribuidor_id',\Auth::user()->id)->where('mac',strtoupper($request->mac))->first();
        if( !$dispositivo )
            return response()->json(['error'=>['mac'=>['Dispositivo não localizado.']]],400);

        if( $request->has('nome') )
            if( ! Helper::validaNome($request->nome) )
                return response()->json(['error'=>['nome'=>['Informe o nome completo.']]],400);

        if( $request->has('cpf') )
            if( ! Helper::validaCPF($request->cpf) )
                return response()->json(['error'=>['cpf'=>['Informe um cpf válido.']]],400);

        if( $request->has('telefone') )
            if( ! Helper::validaCelular($request->telefone) )
                return response()->json(['error'=>['telefone'=>['Informe um telefone válido.']]],400);

        $qtd = 1;
        if( $etapa->tipo == 2)
            $qtd = 2;
        if( $etapa->tipo == 3)
            $qtd = 3;
        if( $request->has('quantidade') )
            $qtd = $request->quantidade;
        
        \DB::beginTransaction();
        try {
            $vendas = array();

            $campos = Input::except( 'id', '_method', '_token', 'quantidade' );
            $campos['ip'] = $request->ip();
            $campos['etapa_id'] = $etapa->id;
            $campos['dispositivo_id'] = $dispositivo->id;

            for( $i=0; $i<$qtd; $i++ ){ 
                // calcula saldo do intervalo
                $inicio = $etapa->range_inicial;
                if( isset( $vendas[($i-1)] ) )
                    $inicio = $vendas[($i-1)]->matriz_id + $etapa->intervalo;
                // seleciona o id do titulo disponivel mais próximo
                $campos['matriz_id'] = Matriz::whereBetween( 'id', [ 
                                            $inicio, 
                                            $etapa->range_final
                                        ])
                                        ->whereNotIn( 'id',
                                            Venda::select('matriz_id')
                                            ->whereNotNull('matriz_id')
                                            ->where( 'etapa_id', $etapa->id )
                                            ->distinct()
                                            ->get()
                                            ->pluck('matriz_id')
                                            ->toArray()
                                        )
                                        ->first()
                                        ->id;
                // cadastra a venda
                $vendas[] = Venda::create( $campos );
            }

            foreach( $vendas as $venda ){
                $matriz = Matriz::find($venda->matriz_id);
                $venda->combinacoes = $matriz->combinacoes;
            }

            \DB::commit();
            return response()->json(['message'=>'Criado com sucesso','redirectURL'=>url('/vendas'),'venda'=>$vendas],201);
        } catch( \Exception $e ){
            \DB::rollback();
            return response()->json(['error'=>$e->getMessage()],404);
        }
    }
    
    public function show( Request $request, $id ){
        try {
            return response()->json( Venda::findOrFail($id) );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }
    
    public function edit( Request $request, $id ){
        $dispositivos = Dispositivo::get();
        $venda = Venda::findOrFail($id);
        $matriz = Matriz::find($venda->matriz_id);
        $venda->combinacoes = $matriz->combinacoes;
        $etapa = Etapa::find($venda->etapa_id);
        return view('venda.form',[ 'venda' => $venda, 'dispositivos' => $dispositivos, 'etapa_id' => $etapa->id, 'showQuantidade' => 0 ]);
    }
    
    public function update( Request $request, $id ){

        $venda = Venda::find($id);
        $inputs = Input::except( 'id', '_method', '_token', 'quantidade' );
        foreach( $inputs as $key => $value ){
            $venda->$key = $value;
        }
        $venda->save();
        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/vendas'), 
            'venda' => $venda 
        ], 200 );
    }
    
    public function destroy( Request $request, $id ){
        $venda = Venda::findOrFail($id);
        $venda->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
    
}
