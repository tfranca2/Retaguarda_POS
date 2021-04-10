<?php

namespace App\Http\Controllers;

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
        $vendas = Venda::paginate(10);
        return response()->json( $vendas, 200 );
    }

    public function index( Request $request ){
        $vendas = Venda::paginate(10);
        return view('venda.index',[ 'vendas' => $vendas ]);
    }

    public function create( Request $request ){
        $dispositivos = Dispositivo::get();
        $etapas = Etapa::get();
        return view('venda.form',[ 'dispositivos' => $dispositivos, 'etapas' => $etapas ]);
    }
    
    public function store( Request $request ){

        $validators = [
            'chave' => 'required|max:255',
            // 'nome' => 'required|max:255',
            'cpf' => 'required_if:telefone,""',
            'telefone' => 'required_if:cpf,""',
        ];

        $etapa = Etapa::where('ativa','1')->first();
        if( in_array( $etapa->tipo, [ 4, 5 ] ) )
            $validators['quantidade'] = 'required|integer|between:1,3';
        
        $validator = Validator::make($request->all(),$validators);
        if( $validator->fails() )
            return response()->json(['error'=>$validator->messages()],400);

        // ******************************************* //
        // *************    MUDAR    ***************** //
        // ******************************************* //
        // $dispositivo_id = Dispositivo::where('user_id',\Auth::user()->id)->where('mac',$request->chave)->first()->id;
        $dispositivo_id = 1;
        // ******************************************* //
        // ******************************************* //
        // ******************************************* //

        $qtd = 1;
        if( $etapa->tipo == 2)
            $qtd = 2;
        if( $etapa->tipo == 3)
            $qtd = 3;
        if( $request->has('quantidade') )
            $qtd = $request->quantidade;
        
        \DB::beginTransaction();
        try {
            $venda = array();

            $campos = Input::except( 'id', '_method', '_token', 'quantidade' );
            $campos['ip'] = $request->ip();
            $campos['etapa_id'] = $etapa->id;
            $campos['dispositivo_id'] = $dispositivo_id;

            for( $i=0; $i<$qtd; $i++ ){ 
                // calcula saldo do intervalo
                $inicio = $etapa->range_inicial;
                if( isset( $venda[($i-1)] ) )
                    $inicio = $venda[($i-1)]->matriz_id + $etapa->intervalo;
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
                $venda[] = Venda::create( $campos );
            }

            \DB::commit();
            return response()->json(['message'=>'Criado com sucesso','redirectURL'=>url('/vendas'),'venda'=>$venda],201);
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
        $etapas = Etapa::get();
        $venda = Venda::findOrFail($id);
        return view('venda.form',[ 'venda' => $venda, 'dispositivos' => $dispositivos, 'etapas' => $etapas ]);
    }
    
    public function update( Request $request, $id ){

        $venda = Venda::find($id);
        $inputs = Input::except( 'id', '_method', '_token' );
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
