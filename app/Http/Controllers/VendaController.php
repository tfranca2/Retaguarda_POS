<?php

namespace App\Http\Controllers;

use Helper;
use App\User;
use App\Venda;
use App\Etapa;
use Validator;
use App\Matriz;
use App\VendaMatriz;
use App\Dispositivo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class VendaController extends Controller
{

    public function filter( Request $request ){

        if( !$request->has('etapa_id') )
            $request->merge([ 'etapa_id' => Etapa::ativa()->id ]);

        if( !Helper::temPermissao('vendas-gerenciar') ) {
            $request->merge([ 'distribuidor_id' => \Auth::user()->id ]);
        }

        $vendas = new Venda();
        $vendas = $vendas->newQuery();

        if( $request->has('etapa_id') )
            if( intval( $request->input('etapa_id') ) )
                $vendas->where('etapa_id',$request->etapa_id);

        if( $request->has('distribuidor_id') )
            if( intval( $request->input('distribuidor_id') ) )
                $vendas->whereHas('dispositivo', function ($query) use ($request) {
                    $query->where('distribuidor_id',  Helper::onlyNumbers( $request->input('distribuidor_id') ) );
                });

        if( $request->has('dispositivo_id') )
            if( intval( $request->input('dispositivo_id') ) )
                $vendas->where('dispositivo_id',$request->dispositivo_id);

        if( $request->has('inicio') )
            if( $request->input('inicio') )
                $vendas->where('created_at','>',$request->inicio.' 00:00:00');

        if( $request->has('fim') )
            if( $request->input('fim') )
                $vendas->where('created_at','<',$request->fim.' 23:59:59');

        return $vendas->orderBy('created_at','DESC')
                    ->with('etapa')
                    ->with('dispositivo')
                    ->with('matrizes');

        // return Venda::orderBy('created_at','DESC')->paginate(10);

    }

    public function getAll( Request $request ){
        return response()->json( Self::filter( $request )->paginate(10), 200 );
    }

    public function index( Request $request ){

        $vendas = Self::filter( $request );

        $totalVendas = 0;
        $totalComissao = 0;
        foreach( $vendas->get() as $venda ){
            if( count( $venda->matrizes ) == 2 ){
                $totalVendas += $venda->etapa->valor_duplo;
                $totalComissao += $venda->etapa->v_comissao_duplo;
            } elseif( count( $venda->matrizes ) == 3 ){
                $totalVendas += $venda->etapa->valor_triplo;
                $totalComissao += $venda->etapa->v_comissao_triplo;
            } else {
                $totalVendas += $venda->etapa->valor_simples;
                $totalComissao += $venda->etapa->v_comissao_simples;
            }
        }
        $totalVendas = Helper::formatDecimalToView( $totalVendas );
        $totalComissao = Helper::formatDecimalToView( $totalComissao );

        $etapas = Etapa::orderBy('id','DESC')->get();

        $dispositivo = null;
        if( $request->has('dispositivo_id') and intval($request->dispositivo_id) )
            $dispositivo = Dispositivo::find($request->dispositivo_id);

        if( Helper::temPermissao('vendas-gerenciar') ) {
            $idPerfilAdmin = \DB::table('permissions')->where('role','vendas-gerenciar')->pluck('perfil_id')->toArray();
            $distribuidores = User::whereNotIn('perfil_id',$idPerfilAdmin)->orderBy('name','ASC')->get();
        } else {
            $distribuidores = User::where( 'id', \Auth::user()->id )->orderBy('name','ASC')->get();
        }

        return view('venda.index',[ 'vendas' => $vendas->paginate(10), 'etapas' => $etapas, 'distribuidores' => $distribuidores, 'dispositivo' => $dispositivo,  'totalVendas' => $totalVendas,  'totalComissao' => $totalComissao, ]);
    }

    public function csv( Request $request ){

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; charset=UTF-8; filename=". date('YmdHis') ."_vendas.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $vendas = Self::filter( $request )->get();

        $callback = function() use ( $vendas ){

            $totalVendas = 0;
            $totalComissao = 0;

            $file = fopen('php://output', 'w');
            fputcsv($file, [ 
                'Etapa', 
                'Distribuidor', 
                'Dispositivo', 
                'Valor', 
                utf8_decode( 'Comissão' ), 
                'Data ', 
            ], ';', '"', "\n");

            foreach( $vendas as $venda ){

                if( count( $venda->matrizes ) == 2 ){
                    $totalVendas += $venda->etapa->valor_duplo;
                    $totalComissao += $venda->etapa->v_comissao_duplo;
                    $valor = $venda->etapa->valor_duplo;
                    $comissao = $venda->etapa->v_comissao_duplo;
                } elseif( count( $venda->matrizes ) == 3 ){
                    $totalVendas += $venda->etapa->valor_triplo;
                    $totalComissao += $venda->etapa->v_comissao_triplo;
                    $valor = $venda->etapa->valor_triplo;
                    $comissao = $venda->etapa->v_comissao_triplo;
                } else {
                    $totalVendas += $venda->etapa->valor_simples;
                    $totalComissao += $venda->etapa->v_comissao_simples;
                    $valor = $venda->etapa->valor_simples;
                    $comissao = $venda->etapa->v_comissao_simples;
                }

                fputcsv( $file, [ 
                    utf8_decode( $venda->etapa->descricao ), 
                    utf8_decode( $venda->dispositivo->distribuidor->name ), 
                    utf8_decode( $venda->dispositivo->nome ), 
                    Helper::formatDecimalToView( $valor ), 
                    Helper::formatDecimalToView( $comissao ), 
                    $venda->created_at, 
                ], ';', '"', "\n" );
            }

            fputcsv($file, [ '', '', '', Helper::formatDecimalToView( $totalVendas ), Helper::formatDecimalToView( $totalComissao ), '' ], ';', '"', "\n");
            fclose($file);
        };

        return \Response::stream( $callback, 200, $headers );

    }

    public function create( Request $request ){
        $dispositivos = Dispositivo::get();
        $etapa = Etapa::ativa();
        return view('venda.form',[ 'dispositivos' => $dispositivos, 'etapa' => $etapa ]);
    }
    
    public function store( Request $request ){

        $validators = [
            'dispositivo_id' => 'required_if:pdv,""|integer',
            'pdv' => 'required_if:dispositivo_id,""|max:255',
            // 'nome' => 'required|max:255',
            'cpf' => 'required|max:255',
            'telefone' => 'required|max:255',
        ];

        $etapa = Etapa::ativa();
        // if( $request->has('etapa_id') )
        //     $etapa = Etapa::find($request->etapa_id);
        if( !$etapa )
            return response()->json(['error'=>['etapa'=>['Etapa não localizada.']]],400);

        // etapa passou do prazo
        if( strtotime( $etapa->data.' 23:59:59' ) < strtotime( date('Y-m-d H:i:s') ) )
            return response()->json(['error'=>['etapa'=>['Etapa inválida.']]],400);

        if( $etapa->tipo == 4 ) // simples e dupla
            $validators['quantidade'] = 'required|integer|in:1,2';

        if( $etapa->tipo == 5 ) // simples e tripla
            $validators['quantidade'] = 'required|integer|in:1,3';
        
        $validator = Validator::make($request->all(),$validators);
        if( $validator->fails() )
            return response()->json(['error'=>$validator->messages()],400);

        $dispositivo = null;
        if( $request->has('dispositivo_id') ){
            $dispositivo = Dispositivo::find($request->dispositivo_id);
            if( !$dispositivo )
                return response()->json(['error'=>['mac'=>['Dispositivo não localizado.']]],400);
        }

        // if( $request->has('nome') )
        //     if( ! Helper::validaNome($request->nome) )
        //         return response()->json(['error'=>['nome'=>['Informe o nome completo.']]],400);

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

        // validar se bilhete consta como vendido
        if( $request->has('bilhete') ){
            $qtd = 0;

            $bilhetes = $request->bilhete;
            if( ! is_array( $request->bilhete ) )
                $bilhetes = array( $request->bilhete );

            $pesquisaBilhete = VendaMatriz::join('vendas','vendas.id','=','venda_id')
                                ->where('etapa_id', $etapa->id)
                                ->whereIn('matriz_id', $bilhetes)
                                ->get();
            $vendido = count( $pesquisaBilhete );

            if( $vendido ){

                $str_bilhete = array();
                foreach( $pesquisaBilhete as $bilhete ){
                    $str_bilhete[] = $bilhete->matriz_id;
                }

                $plural = ((count($str_bilhete)>1)?'s':'');
                return response()->json(['error'=>['bilhete'=>[
                    'Bilhete'. $plural .' [ '. implode(', ', $str_bilhete) .' ] vendido'. $plural .', selecione outro'. $plural .'.'
                ]]],400);
            }

        }
        
        \DB::beginTransaction();
        try {

            $venda = Input::except( 'id', '_method', '_token', 'quantidade' );
            $venda['ip'] = $request->ip();
            $venda['etapa_id'] = $etapa->id;
            if( $dispositivo )
                $venda['dispositivo_id'] = $dispositivo->id;

            $venda['ceder_resgate'] = 1;
            if( $request->has('ceder_resgate') )
                $venda['ceder_resgate'] = $request->ceder_resgate;

            $venda['key'] = Str::uuid();
            $venda = Venda::create( $venda );

            $matriz_id = 0;
            for( $i=0; $i<$qtd; $i++ ){ 
                // calcula saldo do intervalo
                $inicio = $etapa->range_inicial;
                if( $matriz_id )
                    $inicio = $matriz_id + $etapa->intervalo;
                // seleciona o id do titulo disponivel mais próximo
                $matriz_id = Matriz::whereBetween( 'id', [ 
                                            $inicio, 
                                            $etapa->range_final + ( $etapa->intervalo * $i )
                                        ])
                                        ->whereNotIn( 'id',
                                            VendaMatriz::select('matriz_id')
                                            ->whereNotNull('matriz_id')
                                            ->join('vendas','vendas.id','=','venda_id')
                                            ->where('etapa_id', $etapa->id)
                                            ->distinct()
                                            ->get()
                                            ->pluck('matriz_id')
                                            ->toArray()
                                        )
                                        ->first()
                                        ->id;
                VendaMatriz::create([ 
                    'venda_id' => $venda->id, 
                    'matriz_id' => $matriz_id 
                ]);

            }

            if( $request->has('bilhete') ){
                foreach( $request->bilhete as $matriz_id ){ 
                    VendaMatriz::create([ 
                        'venda_id' => $venda->id, 
                        'matriz_id' => $matriz_id 
                    ]);
                }
            }

            $venda = Venda::with('matrizes')->find( $venda->id );

            \DB::commit();
            return response()->json([
                'message'=>'Criado com sucesso',
                'comprovanteURL' => url('/comprovante/'.$venda->key),
                'venda'=>$venda,
                'redirectURL'=>url('/vendas').'/'.$venda->id.'/edit',
            ],201);
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
        $venda = Venda::with('matrizes')->find($id);
        $etapa = Etapa::find($venda->etapa_id);
        return view('venda.form',[ 'venda' => $venda, 'dispositivos' => $dispositivos, 'etapa' => $etapa ]);
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
    
    public function confirmar( Request $request, $id ){

        $venda = Venda::findOrFail($id);
        $venda->confirmada = 1;
        $venda->save();

        return response()->json([
            'message' => 'Confirmado com sucesso',
            'comprovanteURL' => url('/comprovante/'.$venda->key),
            'venda' => $venda,
            'redirectURL' => url('/vendas'),
        ], 200 );

    }
    
    public function destroy( Request $request, $id ){
        $venda = Venda::findOrFail($id);
        $venda->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
    
    public function comprovante( Request $request, $key ){
        $venda = Venda::with('etapa')->with('matrizes')->where('key',$key)->where('confirmada',1)->first();
        if( $venda )
            return view('venda.comprovante',[ 'venda' => $venda ]);
        abort(404);
    }

    public function correios( Request $request){
        $validators = [
            'codigoCorreios' => 'required|string',
        ];
        $validator = Validator::make($request->all(),$validators);
        if( $validator->fails() )
            return response()->json(['error'=>$validator->messages()],400);

        $etapa = Etapa::ativa();
        return view('venda.correios',[ 'etapa' => $etapa, 'codigoCorreios' => $request->codigoCorreios ]);
    }

    public function correiosSalvar( Request $request){
        $validators = [
            'nome' => 'required|max:255',
            'cpf' => 'required|max:255',
            'telefone' => 'required|max:255',
            'telefone' => 'required|max:255',
            'cidade_id' => 'required|integer|exists:cidades,id',
        ];

        $etapa = Etapa::ativa();
        // if( $request->has('etapa_id') )
        //     $etapa = Etapa::find($request->etapa_id);
        if( !$etapa )
            return response()->json(['error'=>['etapa'=>['Etapa não localizada.']]],400);

        // etapa passou do prazo
        if( strtotime( $etapa->data.' 23:59:59' ) < strtotime( date('Y-m-d H:i:s') ) )
            return response()->json(['error'=>['etapa'=>['Etapa inválida.']]],400);

        if( $etapa->tipo == 4 ) // simples e dupla
            $validators['quantidade'] = 'required|integer|in:1,2';

        if( $etapa->tipo == 5 ) // simples e tripla
            $validators['quantidade'] = 'required|integer|in:1,3';
        
        $validator = Validator::make($request->all(),$validators);
        if( $validator->fails() )
            return response()->json(['error'=>$validator->messages()],400);

        $dispositivo = null;
        if( $request->has('dispositivo_id') ){
            $dispositivo = Dispositivo::find($request->dispositivo_id);
            if( !$dispositivo )
                return response()->json(['error'=>['mac'=>['Dispositivo não localizado.']]],400);
        }

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

            $venda = Input::except( 'id', '_method', '_token', 'quantidade' );
            $venda['ip'] = $request->ip();
            $venda['etapa_id'] = $etapa->id;
            $venda['ceder_resgate'] = 1;
            $venda['pdv'] = 'correios';
            $venda['key'] = Str::random(10).time();
            $venda = Venda::create( $venda );

            $matriz_id = 0;
            for( $i=0; $i<$qtd; $i++ ){ 
                // calcula saldo do intervalo
                $inicio = $etapa->range_inicial;
                if( $matriz_id )
                    $inicio = $matriz_id + $etapa->intervalo;
                // seleciona o id do titulo disponivel mais próximo
                $matriz_id = Matriz::whereBetween( 'id', [ 
                                            $inicio, 
                                            $etapa->range_final + ( $etapa->intervalo * $i )
                                        ])
                                        ->whereNotIn( 'id',
                                            VendaMatriz::select('matriz_id')
                                            ->whereNotNull('matriz_id')
                                            ->join('vendas','vendas.id','=','venda_id')
                                            ->where('etapa_id', $etapa->id)
                                            ->distinct()
                                            ->get()
                                            ->pluck('matriz_id')
                                            ->toArray()
                                        )
                                        ->first()
                                        ->id;
                VendaMatriz::create([ 
                    'venda_id' => $venda->id, 
                    'matriz_id' => $matriz_id 
                ]);

            }

            $venda = Venda::with('matrizes')->find( $venda->id );

            $valor = 0;
            if( count( $venda->matrizes ) == 2 )
                $valor = $venda->etapa->valor_duplo;
            elseif( count( $venda->matrizes ) == 3 )
                $valor = $venda->etapa->valor_triplo;
            else
                $valor = $venda->etapa->valor_simples;

            $valor = Helper::onlyNumbers($valor);

            $matrizes = "";
            foreach( $venda->matrizes as $matriz ){
                $chunk = explode( '-', $matriz->matriz->combinacoes );
                foreach( $chunk as $k => $c ){
                    $matrizes .= $c.' ';
                    if( in_array( $k, [ 9, 19 ] ) )
                        $matrizes .= '+';
                }
            }

            // chamar a api correios para registrar atendimento
            $data = [
                'codigoCorreios' => $request->codigoCorreios,
                'valorServico' => $valor,
                'quantidade' => $qtd,
                'numeroIdentificacaoCliente' => Helper::onlyNumbers($request->cpf),
                'chaveCliente' => $venda->key,
                'textoTicket' => "Seus números da sorte são: ++". $matrizes,
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_URL, env('URL_CORREIOS').'/ster/api/v1/atendimentos/registra');
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER,[ 
                "accept: application/json", 
                "Content-Type: application/json", 
                "Authorization: ". env('TOKEN_CORREIOS'),
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $retorno = curl_exec($ch);
            $status_code = curl_getinfo($ch)['http_code'];
            curl_close($ch);

            $response = json_decode($retorno);
            if ($status_code >= 300 || $status_code < 200 || !$response) {
                
                $mensagem = 'API RESPONSE - codigo '.$status_code;
                if( $response and is_object($response) and isset($response->mensagem) )
                    $mensagem .= ': '.$response->mensagem;

                throw new \Exception($mensagem);
            }

            $venda->protocolo = $response->protocolo;
            $venda->save();

            \DB::commit();
            return response()->json([
                'message'=>'Criado com sucesso',
                'comprovanteURL' => url('/comprovante/'.$venda->key),
                'venda'=>$venda,
                'redirectURL'=>url('/vendas').'/'.$venda->id.'/edit',
            ],201);
        } catch( \Exception $e ){
            \DB::rollback();
            return response()->json(['error'=>$e->getMessage()],404);
        }

    }


    public function correiosConfirmarAtendimento( Request $request){
        $validators = [
            'numeroProtocolo' => 'required|string',
            'codigoConfirmacao' => 'required|string',
        ];
        $validator = Validator::make($request->all(),$validators);
        if( $validator->fails() )
            return response()->json(['error'=>$validator->messages()],400);

        $venda = Venda::where('protocolo',$request->numeroProtocolo)->first();

        if($request->codigoConfirmacao == '00'){
            
            $venda->confirmada = true;
            $venda->save();

            return response()->json([ 
                'message' => 'Venda confirmada',
                'codigo' => $venda->key,
            ],200);
        } else {
            
            VendaMatriz::where('venda_id',$venda->id)->delete();
            Venda::where('id',$venda->id)->delete();

            return response()->json([ 'message'=>'Venda removida' ],204);
        }
    }
    
}
