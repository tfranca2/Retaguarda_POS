<?php

namespace App\Http\Controllers;

use DB;
use Mail;
use Session;
use Redirect;
use App\User;
use Validator;
use App\Empresa;
use App\Servico;
use App\Cliente;
use App\Prestador;
use App\Token_fcm;
use App\Contratacao;
use App\Payment;
use App\Helpers\Helper;
use App\Mail\SendMailUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;

class ServicoController extends Controller
{

    public function getAll( Request $request ){

        $servicos = Servico::orderBy('nome')->paginate(10);
        return response()->json( $servicos, 200 );

    }
    
    public function index( Request $request ){

    	$servicos = Servico::orderBy('nome')->withTrashed()->paginate(10);
    	return view('servico.index',[ 'servicos' => $servicos ]);

    }

    public function create( Request $request ){

    	return view('servico.form');

    }
    
    public function store( Request $request ){

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $servico['nome'] = $request->nome;

        if( $request->has('ativo') )
            $servico['deleted_at'] = null;
        else 
            $servico['deleted_at'] = date('Y-m-d H:i:s');

        $servico['pos_servico'] = json_encode( explode(',', $request->pos_servico ) );

        // campos
        $campos = array();
        foreach( $request->nome_campo as $key => $value ){
            if( $key ){
                $campo['nome_campo'] = $value;
                $campo['tipo_campo'] = $request->tipo_campo[ $key ];
                $campo['valor_padrao'] = $request->valor_padrao[ $key ];
                $campo['icone_campo'] = $request->icone_campo[ $key ];

                $campos[] = $campo;
            }
        }
        $servico['campos'] = json_encode( $campos );

        // dd( $servico );
        $servico = Servico::create($servico);

    	return response()->json([ 'message' => 'Criado com sucesso', 'redirectURL' => url('/servicos'), 'servico' => $servico ], 201 );

    }
    
    public function show( Request $request, $id ){

        try {

    	   return response()->json( Servico::withTrashed()->findOrFail($id) );

        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }

    }
    
    public function edit( Request $request, $id ){

        $servico = Servico::withTrashed()->findOrFail($id);
    	return view('servico.form',[ 'servico' => $servico ]);

    }
    
    public function update( Request $request, $id ){

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $servico = Servico::withTrashed()->find($id);
        $servico->nome = $request->nome;
        if( $request->has('ativo') )
        	$servico->deleted_at = null;
        else 
        	$servico->deleted_at = date('Y-m-d H:i:s');

        $servico->pos_servico = json_encode( explode(',', $request->pos_servico ) );

        // campos
        $campos = array();
        foreach( $request->nome_campo as $key => $value ){
            if( $key ){
                $campo['nome_campo'] = $value;
                $campo['tipo_campo'] = $request->tipo_campo[ $key ];
                $campo['valor_padrao'] = $request->valor_padrao[ $key ];
                $campo['icone_campo'] = $request->icone_campo[ $key ];

                $campos[] = $campo;
            }
        }
        $servico->campos = json_encode( $campos );

        $servico->save();

    	return response()->json([ 'message' => 'Atualizado com sucesso', 'redirectURL' => url('/servicos'), 'servico' => $servico ], 200 );

    }
    
    public function destroy( Request $request, $id ){

    	$servico = Servico::withTrashed()->findOrFail($id);
        $servico->forceDelete();

    	return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }

    public function mostrar( Request $request, $id ){
        $servico = Servico::withTrashed()->findOrFail($id);
        return view('servico.mostrar',[ 'servico' => $servico ]);
    }

    public function carregarCampos( Request $request, $id ){
        $servico = Servico::withTrashed()->findOrFail($id);
        return view('servico.campos',[ 'servico' => $servico ]);
    }

    public function contratacao( Request $request ){

        $servicos = Servico::All();
        $clientes = Cliente::All();

        return view('servico.contratar',[ 'servicos' => $servicos, 'clientes' => $clientes ]);

    }

    public function contrato( Request $request ){

        $validator = Validator::make($request->all(), [
            'data' => 'required',
            'hora' => 'required',
            // 'cliente_id' => 'required',
            'servico_id' => 'required',
            // 'prestador_id' => 'required',
            'recorrencia' => 'required',
        ]);

        $cliente = Cliente::where( 'usuario_id', \Auth::user()->id )->first();
        @$cliente_id = $cliente->id;
        if( $request->has('cliente_id') )
            $cliente_id = $request->cliente_id;

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $inicio = $request->data[0] .' '. $request->hora[0];
        $fim = $request->data[1] .' '. $request->hora[1];

        // Comparar se fim é maior que inicio
        if( strtotime( $inicio ) > strtotime( $fim ) )
            return response()->json([ 'error' => 'Datas inválidas' ], 400 );

        // Comparar se inicio maior que hoje
        if( strtotime( $inicio ) <= strtotime( date('Y-m-d').' 23:59:59' ) )
            return response()->json([ 'error' => 'Serviço só pode ser agendado a partir de amanhã' ], 400 );

        if( $request->prestador_id and $request->prestador_id > 1 ){
            // Verifica choque de horários do prestador
            $choqueDeHorario = Contratacao::where( 'prestador_id', $request->prestador_id )
                        ->where( 'cliente_id', '<>', $cliente_id )
                        ->whereRaw( "( ( inicio BETWEEN '$inicio' AND '$fim' ) 
                                   OR  ( fim BETWEEN '$inicio' AND '$fim' ) )" )
                        ->exists();

            if( $choqueDeHorario ){
                return response()->json([ 'error' => 'Choque de horário do prestador de serviço' ], 400 );
            }
        }

        $contratacao['cliente_id'] = $cliente_id;
        $contratacao['servico_id'] = $request->servico_id;
        $contratacao['inicio'] = $inicio;
        $contratacao['fim'] = $fim;
        $contratacao['tipo_imovel'] = $request->tipo_imovel;
        $contratacao['valor'] = Helper::formatDecimalToDb( $request->valor );
        $contratacao['observacoes'] = $request->observacoes;

        $campos = array();

        if( isset( $cliente ) ){
            if( isset( $cliente->condominio ) and $cliente->condominio )
                $campos[] = array( "nome" => "condominio", "valor" => $cliente->condominio );
            
            if( isset( $cliente->unidade ) and $cliente->unidade )
                $campos[] = array( "nome" => "unidade", "valor" => $cliente->unidade );
            
            if( isset( $cliente->bloco ) and $cliente->bloco )
                $campos[] = array( "nome" => "bloco", "valor" => $cliente->bloco );
            
            if( isset( $cliente->cep ) and $cliente->cep )
                $campos[] = array( "nome" => "cep", "valor" => $cliente->cep );
            
            if( isset( $cliente->endereco ) and $cliente->endereco )
                $campos[] = array( "nome" => "endereco", "valor" => $cliente->endereco );
            
            if( isset( $cliente->numero ) and $cliente->numero )
                $campos[] = array( "nome" => "numero", "valor" => $cliente->numero );
            
            if( isset( $cliente->complemento ) and $cliente->complemento )
                $campos[] = array( "nome" => "complemento", "valor" => $cliente->complemento );
            
            if( isset( $cliente->bairro ) and $cliente->bairro )
                $campos[] = array( "nome" => "bairro", "valor" => $cliente->bairro );
            
            if( isset( $cliente->cidade ) and $cliente->cidade )
                $campos[] = array( "nome" => "cidade", "valor" => $cliente->cidade );
            
            if( isset( $cliente->estado ) and $cliente->estado )
                $campos[] = array( "nome" => "estado", "valor" => $cliente->estado );
            
            if( isset( $cliente->latitude ) and $cliente->latitude )
                $campos[] = array( "nome" => "latitude", "valor" => $cliente->latitude );
            
            if( isset( $cliente->longitude ) and $cliente->longitude )
                $campos[] = array( "nome" => "longitude", "valor" => $cliente->longitude );
        }

        $inputs = Input::except( '_method', '_token', 'cliente_id', 'servico_id', 'data', 'hora', 'tipo_imovel', 'valor', 'prestador_id', 'observacoes' );
        foreach( $inputs as $key => $value ){
            $campo['nome'] = $key;
            $campo['valor'] = $value;

            $campos[] = $campo;
        }
        $contratacao['campos'] = json_encode( $campos );

        if( $request->prestador_id and $request->prestador_id > 1 ){
            $contratacao['prestador_id'] = $request->prestador_id;
            $msgFinalizacao = 'Cadastrado com sucesso';
        }

        $request->contratacao = Contratacao::create( $contratacao );

        // return Self::notificarPrestadores( $request, (object) array( 'id' => $pedido_id ));

        // redireciona para o pagamento
        // return Self::checkout( $request );

        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'));
        // curl_setopt($curl, CURLOPT_URL, env('PAGSEGURO_URL', '') ."sessions?appId=".env('PAGSEGURO_APP_ID')."&appKey=".env('PAGSEGURO_APP_KEY') );
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // $sessionID = curl_exec($curl);
        // curl_close($curl);

        // if( $sessionID == 'Unauthorized' ){
        //     $sessionID = null;
        // } else {
        //     // GET SESSION ID
        //     $resp = (Array) new \SimpleXMLElement( $sessionID );
        //     $sessionID = $resp['id'];
        // }

        return response()->json([ 
            'message' => 'Prossiga com o pagamento para confirmar o agendamento', 
            'contratacao_id' => $request->contratacao->id, 
            // 'sessionID' => $sessionID, 
            'redirectURL' => url('/servicos/checkout') .'?cliente_id='. $cliente_id .'&pedido_id='. $request->contratacao->id .'&valor='. $request->valor
        ], 200 );
    }

    public function recorrencia( Request $request ){
        $validator = Validator::make($request->all(), [
            'contratacao_id' => 'required',
            'data' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $contratacao = Contratacao::findOrFail( $request->contratacao_id );
        $recorrenciaServico = '';
        foreach( json_decode($contratacao->campos) as $campo ){
            if( $campo->nome == 'recorrencia' )
                $recorrenciaServico = $campo->valor;
        }

        $request->data = array_unique($request->data);

        if(!$recorrenciaServico)
            return response()->json([ 'error' => 'Recorrência não informada.' ], 400 );
        if( ( $recorrenciaServico == 'uma_x_por_quinzena' or $recorrenciaServico == 'uma_x_por_semana' ) and  count($request->data) != 1 )
            return response()->json([ 'error' => 'Envie 1 data para a recorrência' ], 400 );
        if( $recorrenciaServico == 'tres_x_por_semana' and count($request->data) != 3 )
            return response()->json([ 'error' => 'Envie 3 datas para a recorrência' ], 400 );
        if( $recorrenciaServico == 'cinco_x_por_semana' and count($request->data) != 5 )
            return response()->json([ 'error' => 'Envie 5 datas para a recorrência' ], 400 );

        foreach( $request->data as $data ){
            if( strtotime( $data ) <= strtotime( date('Y-m-d').' 23:59:59' ) )
                return response()->json([ 'error' => 'Datas de recorrência inválidas' ], 400 );
        }

        $dataRecorrencia = array();

        if( $recorrenciaServico == 'uma_x_por_quinzena' ){

            if( date('Y-m-d',strtotime($request->data[0])) != date('Y-m-d',strtotime($contratacao->inicio)) ){
                $dataRecorrencia[] = $request->data[0];
            } else {
                foreach( $request->data as $data ){
                    $diaDaSemana = date('l',strtotime($data));
                    $horario = date('H:i',strtotime($data));
                    $startDate = strtotime($data);
                    $endDate = strtotime('+3 week',$startDate);
                    for( $i = strtotime($diaDaSemana, $startDate ); $i < $endDate; $i = strtotime('+2 week', $i) ){
                        $dataRecorrencia[] = date('Y-m-d', $i).' '.$horario;
                    }
                }
            }


        } elseif( in_array( $recorrenciaServico, [ 'uma_x_por_semana', 'tres_x_por_semana', 'cinco_x_por_semana' ]) ){
            foreach( $request->data as $data ){
                $diaDaSemana = date('l',strtotime($data));
                $horario = date('H:i',strtotime($data));
                $startDate = strtotime($data);
                $endDate = strtotime('+3 week',$startDate);
                for( $i = strtotime($diaDaSemana, $startDate ); $i < $endDate; $i = strtotime('+1 week', $i) ){
                    $dataRecorrencia[] = date('Y-m-d', $i).' '.$horario;
                }
            }
        } else {
            // return response()->json([ 'error' => 'Este serviço não tem recorrência.' ], 400 );
        }

        $msg = '';
        if( $dataRecorrencia ){
            $contratacao->recorrencias = json_encode($dataRecorrencia);
            $contratacao->save();
            $msg = 'Recorrências gravadas ';
        }

        return response()->json([ 'message' => ucfirst($msg.'prossiga com o checkout') ]);

    }

    public function contratar( Request $request, $id ){

        $contratacao = Contratacao::find( $id );
        if(!$contratacao)
            return response()->json([ 'error' => 'Solicitação de serviço não localizada' ], 400 );

        $prestador = Prestador::where( 'usuario_id', \Auth::user()->id )->first();
        if(!$prestador)
            return response()->json([ 'error' => 'Você não é prestador de serviço' ], 400 );

        DB::beginTransaction();

        $contratacoes = Contratacao::where('transaction_code', $contratacao->transaction_code)->get();
        foreach( $contratacoes as $contract ){
            
            $contractId = $contract->id;

            $inicio = $contract->inicio;
            $fim = $contract->fim;

            $choqueDeHorario = Contratacao::where( 'prestador_id', $prestador->id )
                        ->where( 'cliente_id', '<>', $contract->cliente_id )
                        ->whereRaw( "( ( inicio BETWEEN '$inicio' AND '$fim' ) 
                                   OR  ( fim BETWEEN '$inicio' AND '$fim' ) )" )
                        ->exists();
            if( $choqueDeHorario ){
                DB::rollBack();
                return response()->json([ 'error' => 'Choque de horário, confira sua agenda' ], 400 );
            }

            if( $contract->prestador_id and $contract->prestador_id > 1 and $contract->prestador_id != $prestador->id ){
                DB::rollBack();
                return response()->json([ 'error' => 'Serviço já contratado por outro prestador' ], 400 );
            }

            DB::statement("UPDATE contratacao SET prestador_id = ". $prestador->id ." WHERE id = ". $contractId ." AND ( prestador_id IS NULL OR prestador_id = 1 OR prestador_id = 0 OR prestador_id = '' )");

            $contr = Contratacao::find( $contractId );
            if( $contr->prestador_id != $prestador->id ){
                DB::rollBack();
                return response()->json([ 'error' => 'Serviço já contratado por outro prestador' ], 400 );
            }
        }
        
        DB::commit();

        $contratacao = Contratacao::find( $id );

        // notificar o cliente
        return Self::notificarCliente( $request, $contratacao );
    }

    public function calendario( Request $request ){

        $servicos = Servico::All();
        $clientes = Cliente::All();
        $prestadores = Prestador::All();

        return view( 'servico.calendario', [ 'servicos' => $servicos, 'prestadores' => $prestadores, 'clientes' => $clientes ]);

    }

    public function agendamentos( Request $request ){

        $contratacoes = Contratacao::whereNotNull( 'transaction_code' );

        if( $request->inicio and $request->fim ){
            $contratacoes = $contratacoes->where( 'inicio', '>=', $request->inicio )->where( 'inicio', '<=', $request->fim )->orderBy('inicio', 'desc');
        }

        if( ! Helper::temPermissao('servicos-gerenciar') ){

            if( \Auth::user()->perfil_id == 3 ){
                // usuario prestador
                $prestador = Prestador::where('usuario_id',\Auth::user()->id)->first();
                if( $prestador )
                    $contratacoes->where('prestador_id', $prestador->id)->orderBy('inicio', 'desc');
                else
                    return response()->json( array(), 400 );

            } elseif( \Auth::user()->perfil_id == 2 ){
                // usuario cliente
                // $cliente = Cliente::where('email',\Auth::user()->email)->first();
                $cliente = Cliente::where('usuario_id',\Auth::user()->id)->first();
                if( $cliente )
                    $contratacoes->where('cliente_id', $cliente->id)->orderBy('inicio', 'desc');
                else
                    return response()->json( array(), 400 );

            } else {
                return response()->json( array(), 400 );
            }

        }

        $contratacoes = $contratacoes->orderBy('inicio','desc');

        if( !$request->inicio and !$request->fim ){
            $limit = 10;
            if( $request->has('page') and $request->page ){
                $contratacoes = $contratacoes->skip( $limit*($request->page-1) )->take($limit);
            } else {
                $contratacoes = $contratacoes->limit($limit);
            }
        }

        $contratacoes = $contratacoes->get();

        $agenda = array();
        foreach( $contratacoes as $contratacao ){
            
            $event = array();
            $cliente = $contratacao->cliente()->with('usuario')->first();
            $servico = $contratacao->servico()->first();
            $prestador = $contratacao->prestador()->with('usuario')->first();
            if(!$prestador)
                $prestador = Prestador::find(1);
            $pagamento = $contratacao->pagamento()->first();

            $event['id'] = $contratacao->id;
            $event['title'] = $servico->nome;
            $event['nome'] = $prestador->usuario->name;
            $event['status'] = 0;
            if(isset($pagamento))
                $event['status'] = $pagamento->status;
            $event['start'] = date('Y-m-d\TH:i:s', strtotime( $contratacao->inicio ));
            $event['end'] = date('Y-m-d\TH:i:s', strtotime( $contratacao->fim ));
            $event['allDay'] = false;

            $agora = date('Y-m-d H:i:s');
            $event['className'] = 'pendente';
            if( !$contratacao->prestador_id || $contratacao->prestador_id == 1 )
                $event['className'] = 'nao-selecionado';
            elseif( $contratacao->concluido )
                $event['className'] = 'concluido';
            elseif( $contratacao->iniciado )
                $event['className'] = 'iniciado';
            elseif( strtotime( $agora ) > strtotime( $contratacao->inicio ) )
                $event['className'] = 'atrasado';
            elseif( strtotime( $contratacao->inicio ) <= strtotime( $agora ) )
                $event['className'] = 'pendente';

            $event['rating'] = $contratacao->rating;
            
            $event['description'] = 
                '<b>'. date( 'H:i', strtotime( $contratacao->inicio ) ) .' - '. date( 'H:i', strtotime( $contratacao->fim ) ) .'</b><br>'
                . $servico->nome .'<br>';
            if( $cliente->condominio )
                $event['description'] .= $cliente->condominio;
            if( $cliente->bloco )
                $event['description'] .= ' | '. $cliente->bloco;
            if( $cliente->unidade )
                $event['description'] .= ' | '. $cliente->unidade;
            $event['description'] .= '<br>'. $cliente->nome;


            if( $event['className'] == 'nao-selecionado' )
                $event['description'] .= '<br><b>Prestador Indefinido</b>';
            if( $event['className'] == 'concluido' )
                $event['description'] .= '<br><b>Serviço Concluído</b>';
            if( $event['className'] == 'iniciado' )
                $event['description'] .= '<br><b>Serviço Iniciado</b>';
            if( $event['className'] == 'atrasado' )
                $event['description'] .= '<br><b>Serviço Atrasado</b>';
            if( $event['className'] == 'pendente' )
                $event['description'] .= '<br><b>Serviço Ainda não iniciado</b>';

            $event['cep'] = $cliente->cep;
            $event['endereco'] = $cliente->endereco;
            $event['numero'] = $cliente->numero;
            $event['complemento'] = $cliente->complemento;
            $event['bairro'] = $cliente->bairro;
            $event['cidade'] = $cliente->cidade;
            $event['estado'] = $cliente->estado;
            $event['condominio'] = $cliente->condominio;
            $event['unidade'] = $cliente->unidade;
            $event['bloco'] = $cliente->bloco;
            $event['latitude'] = $cliente->latitude;
            $event['longitude'] = $cliente->longitude;

            $agenda[] = $event;
        }

        return response()->json( $agenda , 200);

    }

    public function agendamento( Request $request, $id ){
        
        $contratacao = Contratacao::findOrFail($id);

        $contratacao->campos = json_decode($contratacao->campos);

        $contratacao->cliente = $contratacao->cliente()->with('usuario')->first();
        $contratacao->servico = $contratacao->servico()->first();
        $contratacao->prestador = $contratacao->prestador()->with('usuario')->first();

        if( $contratacao->transaction_code )
            $contratacao->pagamento = $contratacao->pagamento()->first();
        
        if( !$contratacao->prestador )
            $contratacao->prestador = Prestador::find(1)->with('usuario')->first();

        if( isset( $contratacao->prestador ) )
        if( isset( $contratacao->prestador->usuario->imagem ) )
            $contratacao->prestador->usuario->imagem = url('/public/images/'.$contratacao->prestador->usuario->imagem);

        $agora = date('Y-m-d H:i:s');
            $contratacao->className = 'pendente';
        if( !$contratacao->prestador_id || $contratacao->prestador_id == 1 )
            $contratacao->className = 'nao-selecionado';
        elseif( $contratacao->concluido )
            $contratacao->className = 'concluido';
        elseif( $contratacao->iniciado )
            $contratacao->className = 'iniciado';
        elseif( strtotime( $agora ) > strtotime( $contratacao->inicio ) )
            $contratacao->className = 'atrasado';
        elseif( strtotime( $contratacao->inicio ) <= strtotime( $agora ) )
            $contratacao->className = 'pendente';

        if( $contratacao->fotos ){
            $fotos = [];
            foreach( json_decode($contratacao->fotos) as $foto ){
                $fotos[] = url('/public/images/'.$foto);
            }
            if( $fotos )
                $contratacao->fotos = json_encode($fotos);
        }


        return response()->json( $contratacao , 200);

    }

    public function iniciar( Request $request, $id ){
        
        $contratacao = Contratacao::findOrFail($id);
        $contratacao->iniciado = date('Y-m-d H:i:s');
        $contratacao->save();

        return response()->json([ 'message' => 'Iniciado com sucesso' ], 200);

    }

    public function concluir( Request $request ){

        $campos = array();
        $inputs = Input::except( '_method', '_token', '_parts', 'id', 'foto', 'observacoes' );
        foreach( $inputs as $key => $value ){
            $campos[] = $key;
        }

        $fotos = [];
        // UPLOAD IMAGEM
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

        $contratacao = Contratacao::findOrFail($request->id);
        $contratacao->concluido = date('Y-m-d H:i:s');
        if( $fotos )
            $contratacao->fotos = json_encode( $fotos );
        if( $request->has('observacoes') )
            $contratacao->observacoes = $request->observacoes;
        $contratacao->pos_servico = json_encode( $campos );
        $contratacao->save();

        return response()->json([ 'message' => 'Serviço concluído com sucesso', 'redirectURL' => url('/servicos/calendario') ], 200);

    }

    public function valor( Request $request, $servico_id ){

        // dd($request);

        $validator = Validator::make($request->all(), [
            'tipo_imovel' => 'required',
            // 'número_de_quartos' => 'required',
            // 'número_de_banheiros' => 'required',
            'recorrencia' => 'required',
            // 'tipo_de_limpeza' => 'required',
            // 'data' => 'required',
            // 'hora' => 'required',
            // 'servico_id' => 'required',
        ]);

        if( $validator->fails() )
            return response()->json([ 'error' => $validator->messages() ], 400 );

        $diassemana = array( 'domingo' => 0, 'segunda' => 1, 'terca' => 2, 'quarta' => 3, 'quinta' => 4, 'sexta' => 5, 'sabado' => 6 ); 

        $descontoAplicado = 0;
        if( $request->has('data') and $request->has('hora') ){
            if( ! is_array( $request->data ) ){
                $request->data = array( $request->data );
            }

            if( ! is_array( $request->hora ) ){
                $request->hora = array( $request->hora );
            }

            if( $request->data and $request->hora ){
                $descontos = \Auth::user()->empresa()->descontos;
                if( $descontos ){
                    $descontos = json_decode($descontos);
                    foreach( $descontos as $desconto ){
                        if( 
                            $desconto->servico_id == $servico_id and 
                            $diassemana[ $desconto->diasemana ] == date('w', strtotime( $request->data[0] )) and
                            $desconto->inicio <= $request->hora[0] and
                            $desconto->fim >= $request->hora[0] 
                        ){
                            $descontoAplicado = str_replace( ',', '.', $desconto->desconto );
                        }
                    }
                }
            }
        }

        if( $request->tipo_imovel == 'residencial' ){

            $qt_quartos = $request->número_de_quartos;
            $qt_banheiros = $request->número_de_banheiros;

            $area_interna = ( 9+(($qt_quartos-1)*(9*1.7)) );
            $metragem_quartos = ( 20+(($qt_quartos-1)*(20*0.9)) );
            $metragem_banheiros = ( 6+(($qt_banheiros-1)*(6*0.8)) );

            $metragem_apartamento = $area_interna + $metragem_quartos + $metragem_banheiros;

            if( $request->tipo_de_limpeza == 'simples' ){

                // RESIDENCIAL SIMPLES

                if( $metragem_apartamento < 46 ){
                    $tempo_gasto_limpeza_por_metro = 0.857142857142857;
                    $custo_limpeza_metro_quadrado = 0.588296974650637;
                } elseif( $metragem_apartamento < 71 ) {
                    $tempo_gasto_limpeza_por_metro = 0.775862068965517;
                    $custo_limpeza_metro_quadrado = 0.46;
                } elseif( $metragem_apartamento < 101 ) {
                    $tempo_gasto_limpeza_por_metro = 0.775862068965517;
                    $custo_limpeza_metro_quadrado = 0.40;
                } elseif( $metragem_apartamento < 134 ) {
                    $tempo_gasto_limpeza_por_metro = 0.697674418604651;
                    $custo_limpeza_metro_quadrado = 0.397974542541438;
                } else {
                    $tempo_gasto_limpeza_por_metro = 1.01694915254237;
                    $custo_limpeza_metro_quadrado = 0.50885832082313;
                }

                $minutos_limpeza_dedicados_apartamento = $metragem_apartamento * $tempo_gasto_limpeza_por_metro;

                $custo_base_limpeza_diaria = $metragem_apartamento * $custo_limpeza_metro_quadrado;

                // parametros das escalas
                $dias_no_ano = 365;
                $meses_no_ano = 12;
                $dias_na_semana = 7;
                $dias_no_mes = $dias_no_ano/$meses_no_ano;
                $semanas_no_ano = $dias_no_ano/$dias_na_semana;
                $semanas_no_mes = $semanas_no_ano/$meses_no_ano;
                $escala_5_x_2 = ($dias_no_mes/$dias_na_semana)*5;

                // valores
                $limpeza_avulsa = $custo_base_limpeza_diaria*(1+(35/100));
                $uma_x_por_quinzena = ($custo_base_limpeza_diaria*($semanas_no_mes/2))*(1+(25/100));
                $uma_x_por_semana = ($custo_base_limpeza_diaria*($semanas_no_mes))*(1+(20/100));
                $tres_x_por_semana = ($custo_base_limpeza_diaria*($semanas_no_mes*3))*(1+(15/100));
                $cinco_x_por_semana = ($custo_base_limpeza_diaria*($escala_5_x_2))*(1+(0/100));

                $valores = array(
                    'limpeza_avulsa' => round($limpeza_avulsa,2),
                    'uma_x_por_quinzena' => round($uma_x_por_quinzena,2),
                    'uma_x_por_semana' => round($uma_x_por_semana,2),
                    'tres_x_por_semana' => round($tres_x_por_semana,2),
                    'cinco_x_por_semana' => round($cinco_x_por_semana,2),
                );

                $valorFinal = $valores[ $request->recorrencia ] - ( $valores[ $request->recorrencia ] * ( $descontoAplicado / 100 ) );

                return response()->json([ 
                    'run_time' => round( $minutos_limpeza_dedicados_apartamento, 2 ),
                    'm2' => round( $metragem_apartamento , 2),
                    'gross_amount' => $valores[$request->recorrencia],
                    'discount' => $descontoAplicado,
                    'value' => round( $valorFinal, 2 ),
                    'currency' => 'R$', 
                ], 200);

            } elseif( $request->tipo_de_limpeza == 'pesada' ){

                // RESIDENCIAL PESADA

                $peso = 2;
                $fator = 1;
                $custo_equipamento = 15;
                $custo_materiais = 15;

                if( $metragem_apartamento < 46 ){
                    $tempo_gasto_limpeza_por_metro = 0.857142857142857*$peso;
                    $custo_limpeza_metro_quadrado = 0.588296974650637;
                } elseif( $metragem_apartamento < 71 ) {
                    $tempo_gasto_limpeza_por_metro = 0.775862068965517*$peso;
                    $custo_limpeza_metro_quadrado = 0.46;
                } elseif( $metragem_apartamento < 101 ) {
                    $tempo_gasto_limpeza_por_metro = 0.775862068965517*$peso;
                    $custo_limpeza_metro_quadrado = 0.40;
                } elseif( $metragem_apartamento < 134 ) {
                    $tempo_gasto_limpeza_por_metro = 0.697674418604651*$peso;
                    $custo_limpeza_metro_quadrado = 0.397974542541438;
                } else {
                    $tempo_gasto_limpeza_por_metro = 1.01694915254237*$peso;
                    $custo_limpeza_metro_quadrado = 0.50885832082313;
                }

                $minutos_limpeza_dedicados_apartamento = $metragem_apartamento * $tempo_gasto_limpeza_por_metro;

                $custo_base_limpeza_diaria = ( $metragem_apartamento * ($custo_limpeza_metro_quadrado * $peso * $fator ) ) + $custo_equipamento + $custo_materiais;

                // valores
                $limpeza_avulsa = $custo_base_limpeza_diaria*(1+(35/100));
                $uma_x_por_quinzena = 0;
                $uma_x_por_semana = 0;
                $tres_x_por_semana = 0;
                $cinco_x_por_semana = 0;

                $valores = array(
                    'limpeza_avulsa' => round($limpeza_avulsa,2),
                    'uma_x_por_quinzena' => round($uma_x_por_quinzena,2),
                    'uma_x_por_semana' => round($uma_x_por_semana,2),
                    'tres_x_por_semana' => round($tres_x_por_semana,2),
                    'cinco_x_por_semana' => round($cinco_x_por_semana,2),
                );

                $valorFinal = $valores[ $request->recorrencia ] - ( $valores[ $request->recorrencia ] * ( $descontoAplicado / 100 ) );

                return response()->json([ 
                    'run_time' => round( $minutos_limpeza_dedicados_apartamento, 2 ),
                    'm2' => round( $metragem_apartamento , 2),
                    'gross_amount' => $valores[$request->recorrencia],
                    'discount' => $descontoAplicado,
                    'value' => round( $valorFinal, 2 ),
                    'currency' => 'R$', 
                ], 200);

            }

        } elseif( $request->tipo_imovel == 'comercial' ){

            $metragem_apartamento = $request->metragem_total;
            $tempo_gasto_limpeza_por_metro = 0.836907124313850;
            $minutos_limpeza_dedicados_apartamento = $metragem_apartamento * $tempo_gasto_limpeza_por_metro;
            $custo_limpeza_metro_quadrado = 0.588615770853693;

            $custo_base_limpeza_diaria = $metragem_apartamento * $custo_limpeza_metro_quadrado;
            
            // parametros das escalas
            $dias_no_ano = 365;
            $meses_no_ano = 12;
            $dias_na_semana = 7;
            $dias_no_mes = $dias_no_ano/$meses_no_ano;
            $semanas_no_ano = $dias_no_ano/$dias_na_semana;
            $semanas_no_mes = $semanas_no_ano/$meses_no_ano;
            $escala_5_x_2 = ($dias_no_mes/$dias_na_semana)*5;

            // valores
            $limpeza_avulsa = $custo_base_limpeza_diaria*(1+(35/100));
            $uma_x_por_quinzena = ($custo_base_limpeza_diaria*($semanas_no_mes/2))*(1+(25/100));
            $uma_x_por_semana = ($custo_base_limpeza_diaria*($semanas_no_mes))*(1+(20/100));
            $tres_x_por_semana = ($custo_base_limpeza_diaria*($semanas_no_mes*3))*(1+(15/100));
            $cinco_x_por_semana = ($custo_base_limpeza_diaria*($escala_5_x_2))*(1+(0/100));

            $valores = array(
                'limpeza_avulsa' => round($limpeza_avulsa,2),
                'uma_x_por_quinzena' => round($uma_x_por_quinzena,2),
                'uma_x_por_semana' => round($uma_x_por_semana,2),
                'tres_x_por_semana' => round($tres_x_por_semana,2),
                'cinco_x_por_semana' => round($cinco_x_por_semana,2),
            );

            $valorFinal = $valores[ $request->recorrencia ] - ( $valores[ $request->recorrencia ] * ( $descontoAplicado / 100 ) );

            return response()->json([ 
                'run_time' => round( $minutos_limpeza_dedicados_apartamento, 2 ),
                'm2' => round( $metragem_apartamento , 2),
                'gross_amount' => $valores[$request->recorrencia],
                'discount' => $descontoAplicado,
                'value' => round( $valorFinal, 2 ),
                'currency' => 'R$', 
            ], 200);

        }

        return response()->json([ 'error' => 'Tipo de imóvel não atendido.' ], 400);
    }

    public function notificarPrestadores( Request $request, $contratacao = null ){

        // if( ! $request->has('tentativas') )
        //     $request->tentativas = 0;
        // $request->tentativas++;

        if( $request->has('contratacao_id') )
            $contratacao = Contratacao::findOrFail( $request->contratacao_id );
        else
            $contratacao = Contratacao::findOrFail( $contratacao->id );
        $cliente = $contratacao->cliente()->with('usuario')->first();

        $request->request->add([ 
            'latitude' => $cliente->latitude, 
            'longitude' => $cliente->longitude 
        ]);

        $prestadores_que_recusaram = Prestador::whereIn('prestador.id', 
                                    (Array) json_decode( $contratacao->cancelamento_prestador ) )
                                ->join('users','usuario_id','=','users.id')
                                ->pluck('users.email')
                                ->toArray();

        $emails = array();
        $proximos = app('App\Http\Controllers\PrestadorController')->nasProximidades( $request )->getData();
        if( !isset($proximos->error) ){
            foreach( $proximos as $proximo ){
                $user = User::where( 'id', $proximo->usuario_id )->first();
                if( $user and !in_array( $user->email, $prestadores_que_recusaram ) )
                    $emails[] = $user->email;
            }
        }

        // if( $request->tentativas <= 3 and ! $emails ){
        //     $request->distancia = 10 + ( 5 * ( $request->tentativas - 1 ) ); // Km
        //     return Self::notificarPrestadores( $request, $contratacao );
        // }

        if( $emails ){
            $recipients = Token_fcm::join('users','user_id','=','users.id')
                                ->whereIn( 'users.email', $emails )
                                ->whereNotNull( 'device_token' )
                                ->pluck('fcm_token')->toArray();
            $recipients = array_unique($recipients);

            if( $recipients )
                $notify =   fcm()->to($recipients)
                            ->priority('high')
                            ->timeToLive(0)
                            ->notification([
                                'type' => "push::type::notify",
                                'priority' => 'high',
                                'show_in_foreground' => true,
                                'show_foreground' => true,
                                // 'content_available' => true,
                                // 'content-available' => true,
                                'sound' => 'default',
                                'title' => 'Novo serviço disponível em sua área!',
                                'body' => 'Mensagem broadcast para aceitar serviço',
                                'contratacao_id' => $contratacao->id,
                                // 'contratacao' => $contratacao,
                            ])->data([
                                'type' => "push::type::notify",
                                'priority' => 'high',
                                'show_in_foreground' => true,
                                'show_foreground' => true,
                                // 'content_available' => true,
                                // 'content-available' => true,
                                'sound' => 'default',
                                'title' => 'Novo serviço disponível em sua área!',
                                'body' => 'Mensagem broadcast para aceitar serviço',
                                'contratacao_id' => $contratacao->id,
                                // 'contratacao' => $contratacao,
                            ])->send();

        }

        $msgFinalizacao = 'Estamos procurando o prestador de serviço ideal para você. Entraremos em contato em breve.';

        return response()->json([ 
            'message' => $msgFinalizacao, 
            'contratacao_id' => $contratacao->id, 
            'contratacao' => $contratacao,
            'redirectURL' => url('/servicos/calendario') 
        ], 200 );

    }

    public function notificarPagamentoCliente( Request $request, $contratacao ){
        
        $contratacao = Contratacao::findOrFail( $contratacao->id );
        $cliente = $contratacao->cliente()->with('usuario')->first();
        $servico = $contratacao->servico()->first();

        $recipients = Token_fcm::join('users','user_id','=','users.id')
                            ->whereIn( 'users.email', [ $cliente->email ] )
                            ->whereNotNull( 'device_token' )
                            ->pluck('fcm_token')->toArray();
        $recipients = array_unique($recipients);

        if( $recipients ){

            fcm()->to($recipients)
            ->priority('high')
            ->timeToLive(0)
            ->notification([
                'type' => "push::type::notify",
                'priority' => 'high',
                'show_in_foreground' => true,
                'show_foreground' => true,
                // 'content_available' => true,
                // 'content-available' => true,
                'sound' => 'default',
                'title' => 'Pagamento confirmado',
                'body' => 'Pagamento confirmado',
                'contratacao_id' => $contratacao->id,
                // 'contratacao' => $contratacao,
                // 'servico' => $servico,
            ])->data([
                'type' => "push::type::notify",
                'priority' => 'high',
                'show_in_foreground' => true,
                'show_foreground' => true,
                // 'content_available' => true,
                // 'content-available' => true,
                'sound' => 'default',
                'title' => 'Pagamento confirmado',
                'body' => 'Pagamento confirmado',
                'contratacao_id' => $contratacao->id,
                // 'contratacao' => $contratacao,
                // 'servico' => $servico,
            ])->send();

        }

        return response()->json([ 'message' => 'Pagamento confirmado', 'redirectURL' => url('/servicos/calendario') ], 200 );
    }

    public function notificarCliente( Request $request, $contratacao ){

        $cliente = $contratacao->cliente()->with('usuario')->first();
        $servico = $contratacao->servico()->first();
        $prestador = $contratacao->prestador()->with('usuario')->first();

        $recipients = Token_fcm::join('users','user_id','=','users.id')
                            ->whereIn( 'users.email', [ $cliente->email ] )
                            ->whereNotNull( 'device_token' )
                            ->pluck('fcm_token')->toArray();
        $recipients = array_unique($recipients);

        if( $recipients ){

            fcm()->to($recipients)
            ->priority('high')
            ->timeToLive(0)
            ->notification([
                'type' => "push::type::notify",
                'priority' => 'high',
                'show_in_foreground' => true,
                'show_foreground' => true,
                // 'content_available' => true,
                // 'content-available' => true,
                'sound' => 'default',
                'title' => 'Serviço aceito',
                'body' => $contratacao->inicio .': '. $servico->nome .' - '. $prestador->usuario->nome,
                'contratacao_id' => $contratacao->id,
                // 'contratacao' => $contratacao,
                // 'prestador' => $prestador,
                // 'cliente' => $cliente,
            ])->data([
                'type' => "push::type::notify",
                'priority' => 'high',
                'show_in_foreground' => true,
                'show_foreground' => true,
                // 'content_available' => true,
                // 'content-available' => true,
                'sound' => 'default',
                'title' => 'Serviço aceito',
                'body' => $contratacao->inicio .': '. $servico->nome .' - '. $prestador->usuario->nome,
                'contratacao_id' => $contratacao->id,
                // 'contratacao' => $contratacao,
                // 'prestador' => $prestador,
                // 'cliente' => $cliente,
            ])->send();

        }

        Mail::to( $cliente->email )->send( new SendMailUser( 'Serviço aceito', 'email.servicoAceito', 
            [ 
                'servico' => $contratacao->servico->nome, 
                'inicio' => $contratacao->inicio, 
                'prestador' => $prestador->usuario->nome, 
                'empresa' => config('app.name'), 
            ]
        ) );

        return response()->json([ 'message' => 'Serviço contratado com sucesso, confira sua agenda', 'redirectURL' => url('/servicos/calendario') ], 200 );

    }

    public function notificarMudancaAgenda( Request $request, $contratacao, $tipo = 0 ){

        // NOTIFICAR O CLIENTE E O PRESTADOR: PUSH | EMAIL
        // CASOS: 
        //     - CANCELAMETO 
        //     - ALTERAÇÃO DO AGENDAMENTO: DATA | PRESTADOR

        $cliente = $contratacao->cliente()->with('usuario')->first();
        $servico = $contratacao->servico()->first();
        $prestador = $contratacao->prestador()->with('usuario')->first();
        $pagamento = $contratacao->pagamento()->first();


        switch( $tipo ){
            case 1:
                $title = 'Serviço aceito pelo prestador '. $prestador->usuario->name;
                $body = 'Acesse seu calendário para mais detalhes';
            break;
            case 2:
                $title = 'Serviço cancelado pelo cliente';
                $body = 'Acesse seu calendário para mais detalhes';
            break;
            case 3:
                $title = 'Serviço cancelado pelo prestador';
                $body = 'Estamos procurando outro prestador de serviço para sua demanda.';
            break;
            default:
                $title = 'Houveram Mudanças na Agenda';
                $body = 'Acesse seu calendário para mais detalhes';
            break;
        }

        $emails = array( $cliente->email );
        if( $prestador and !in_array( $tipo, [ 1, 3 ]) )
            $emails[] = $prestador->usuario->email;

        $recipients = Token_fcm::join('users','user_id','=','users.id')
                            ->whereIn( 'users.email', $emails )
                            ->whereNotNull( 'device_token' )
                            ->pluck('fcm_token')->toArray();
        $recipients = array_unique($recipients);

        if( $recipients ){
            fcm()->to($recipients)
            ->priority('high')
            ->timeToLive(0)
            ->notification([
                'type' => "push::type::notify",
                'priority' => 'high',
                'show_in_foreground' => true,
                'show_foreground' => true,
                // 'content_available' => true,
                // 'content-available' => true,
                'sound' => 'default',
                'title' => $title,
                'body' => $body,
                'contratacao_id' => $contratacao->id,
                // 'contratacao' => $contratacao,
                // 'prestador' => $prestador,
                // 'cliente' => $cliente,
            ])->data([
                'type' => "push::type::notify",
                'priority' => 'high',
                'show_in_foreground' => true,
                'show_foreground' => true,
                // 'content_available' => true,
                // 'content-available' => true,
                'sound' => 'default',
                'title' => $title,
                'body' => $body,
                'contratacao_id' => $contratacao->id,
                // 'contratacao' => $contratacao,
                // 'prestador' => $prestador,
                // 'cliente' => $cliente,
            ])->send();
        }

        // Mail::to( $cliente->email )->send( new SendMailUser( 'Mudanças na agendada', 'email.', 
        //     [ 
        //         'servico' => $contratacao->servico->nome, 
        //         'inicio' => $contratacao->inicio, 
        //         'prestador' => $prestador->usuario->nome, 
        //         'empresa' => config('app.name'), 
        //     ]
        // ) );

        return response()->json([ 
            'message' => 'Houveram alterações na sua agenda!', 
            'redirectURL' => url('/servicos/calendario') 
        ], 200 );

    }

    public function checkout( Request $request ){

        // SESSION_ID PARA JS PAGSEGURO
        header("access-control-allow-origin: ". env('PAGSEGURO_URL', '') );
        $data = array();
        $data['email'] = env('PAGSEGURO_EMAIL', '');
        $data['token'] = env('PAGSEGURO_TOKEN', '');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'));
        curl_setopt($curl, CURLOPT_URL, env('PAGSEGURO_URL', '') ."v2/sessions");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query( $data ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $sessionID = curl_exec($curl);
        curl_close($curl);

        $erros = array();
        if( $request->erros )
            $erros = $request->erros;

        if( $sessionID ) {
            try {
                $xml = simplexml_load_string( $sessionID );
                $sessionID = $xml->id;
                
                if( !$sessionID )
                    $erros[] = $xml;

            } catch( \Exception $e ) {
                $erros[] = $e->getMessage();
            }
        } else {
            $erros[] = 'Serviço de pagamento temporariamente indisponível';
        }

        $cliente = Cliente::find( $request->cliente_id );

        return view('servico.checkout',[ 
                        "cliente" => $cliente, 
                        "valor" => $request->valor, 
                        "pedido_id" => $request->pedido_id, 
                        "sessionID" => $sessionID, 
                        "erros" => $erros, 
                    ]);
    }

    public function checkout2( Request $request ){

        // SESSION_ID PARA JS PAGSEGURO
        header("access-control-allow-origin: ". env('PAGSEGURO_URL', '') );
        $data = array();
        $data['email'] = env('PAGSEGURO_EMAIL', '');
        $data['token'] = env('PAGSEGURO_TOKEN', '');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'));
        curl_setopt($curl, CURLOPT_URL, env('PAGSEGURO_URL', '') ."v2/sessions");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query( $data ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $sessionID = curl_exec($curl);
        curl_close($curl);

        $erros = array();
        if( $request->erros )
            $erros = $request->erros;

        if( $sessionID ) {
            try {
                $xml = simplexml_load_string( $sessionID );
                $sessionID = $xml->id;
                
                if( !$sessionID )
                    $erros[] = $xml;

            } catch( \Exception $e ) {
                $erros[] = $e->getMessage();
            }
        } else {
            $erros[] = 'Serviço de pagamento temporariamente indisponível';
        }

        $cliente = Cliente::find( $request->cliente_id );

        $id = 0;
        $gorjeta = 0;
        $pedido = Contratacao::find( $request->pedido_id );
        if( $pedido ){
            $id = $request->pedido_id;
            $valor = $pedido->valor;
            $gorjeta = $pedido->gorjeta;
            if( !$gorjeta )
                $gorjeta = $request->gorjeta;
        }

        if( $request->valor )
            $valor = $request->valor;

        if( !$valor ){
            Session::flash('error', "Valor deve ser maior que 0!");
            return view('layouts.public');
        }

        return view('servico.checkout2',[ 
                        "cliente" => $cliente, 
                        "valor" => $valor, 
                        "pedido_id" => $id, 
                        "gorjeta" => $gorjeta, 
                        "sessionID" => $sessionID, 
                        "erros" => $erros, 
                    ]);
    }

    public function checkoutPagar( Request $request ) {

        $nome = Helper::sanitizeString( $request->nome );
        $email = $request->email;
        $cpf = Helper::onlyNumbers( $request->cpf );
        $data_nascimento = $request->data_nascimento;
        $ddd = substr( Helper::onlyNumbers( $request->telefone ), 0, 2);
        $telefone = substr( Helper::onlyNumbers( $request->telefone ), 2 );
        $cep = Helper::onlyNumbers( $request->cep );
        $endereco = Helper::sanitizeString( $request->endereco );
        $numero = Helper::sanitizeString( $request->numero );
        $complemento = Helper::sanitizeString( $request->complemento );
        $bairro = Helper::sanitizeString( $request->bairro );
        $cidade = Helper::sanitizeString( $request->cidade );
        $uf = Helper::sanitizeString( $request->uf );

        $pedido_id = $request->pedido_id;

        $valor = number_format( $request->valor, 2 );
        $gorjeta = Helper::formatDecimalToDb($request->gorjeta);

        // $parcelamento = $request->parcelamento;
        // $parcelaValor = number_format( $request->parcelaValor, 2 );
        $parcelamento = 1;
        $parcelaValor = $valor;

        $identificador = $request->identificador;
        $tokencartao = $request->tokencartao;

        // $plano = $request->plano;
        $plano = 0;

        $cliente = Cliente::find($request->cliente_id);
        if($cliente){
            $cliente->nome = $request->nome;

            $cliente->data_nascimento = $data_nascimento;
            
            $cliente->cpf = $cpf;
            $cliente->telefone = $request->telefone;
            $cliente->cep = $cep;
            $cliente->endereco = $request->endereco;
            $cliente->numero = $request->numero;
            $cliente->complemento = $request->complemento;
            $cliente->bairro = $request->bairro;
            $cliente->estado = $request->uf;
            $cliente->cidade = $request->cidade;
            $cliente->save();
        }

        $data = array();

        if( $plano ){

            $data['plan'] = $plano;
            $data['reference'] = $pedido_id;
            $data['sender']['name'] = $nome;
            $data['sender']['email'] = env('PAGSEGURO_COMPRADOR_EMAIL', $email);
            $data['sender']['ip'] = $_SERVER['REMOTE_ADDR'];
            $data['sender']['hash'] = $identificador;
            $data['sender']['phone']['areaCode'] = $ddd;
            $data['sender']['phone']['number'] = $telefone;
            $data['sender']['address']['street'] = $endereco;
            $data['sender']['address']['number'] = $numero;
            $data['sender']['address']['complement'] = $complemento;
            $data['sender']['address']['district'] = $bairro;
            $data['sender']['address']['city'] = $cidade;
            $data['sender']['address']['state'] = $uf;
            $data['sender']['address']['country'] = "BRA";
            $data['sender']['address']['postalCode'] = $cep;
            $data['sender']['documents'][0]['type'] = "CPF";
            $data['sender']['documents'][0]['value'] = $cpf;
            $data['paymentMethod']['type'] = "CREDITCARD";
            $data['paymentMethod']['creditCard']['token'] = $tokencartao;
            $data['paymentMethod']['creditCard']['holder']['name'] = $nome;
            $dt = explode('-', $data_nascimento);
            $data['paymentMethod']['creditCard']['holder']['birthDate'] = $dt[2].'/'.$dt[1].'/'.$dt[0];
            $data['paymentMethod']['creditCard']['holder']['documents'][0]['type'] = "CPF";
            $data['paymentMethod']['creditCard']['holder']['documents'][0]['value'] = $cpf;
            $data['paymentMethod']['creditCard']['holder']['phone']['areaCode'] = $ddd;
            $data['paymentMethod']['creditCard']['holder']['phone']['number'] = $telefone;
            $data['paymentMethod']['creditCard']['holder']['billingAddress']['street'] = $endereco;
            $data['paymentMethod']['creditCard']['holder']['billingAddress']['number'] = $numero;
            $data['paymentMethod']['creditCard']['holder']['billingAddress']['complement'] = $complemento;
            $data['paymentMethod']['creditCard']['holder']['billingAddress']['district'] = $bairro;
            $data['paymentMethod']['creditCard']['holder']['billingAddress']['city'] = $cidade;
            $data['paymentMethod']['creditCard']['holder']['billingAddress']['state'] = $uf;
            $data['paymentMethod']['creditCard']['holder']['billingAddress']['country'] = "BRA";
            $data['paymentMethod']['creditCard']['holder']['billingAddress']['postalCode'] = $cep;
            $data = json_encode($data);

            header("access-control-allow-origin: ". env('PAGSEGURO_URL', '') );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, env('PAGSEGURO_URL', '') ."pre-approvals?email=". env('PAGSEGURO_EMAIL', '') ."&token=". env('PAGSEGURO_TOKEN', '') );
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=ISO-8859-1", "Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1"));
            $response = curl_exec($ch);
            curl_close($ch);

            if( $response ){
                $response = json_decode( $response );

                if( isset( $response->error ) ){

                    $erros = array();
                    foreach( $resp as $erro ){
                        $msg = $erro->code .': '. $erro->message;
                        $traducao = trans( 'pagseguro.'.$erro->code, [], 'pt-BR' );
                        if( $traducao != 'pagseguro.'.$erro->code ){
                            $msg = $erro->code .': '. $traducao;
                        }
                        $erros[] = $msg;
                    }

                    $request->erros = $erros;

                    if( \Auth::user() )
                    return Self::checkout( $request );
                    else
                    return Self::checkout2( $request );

                } elseif( $response->code ){

                    return Self::notificarPrestadores( $request, (object) array( 'id' => $pedido_id ));

                    // CANCELAR PAGAMENTO RECORRENTE

                    // header("access-control-allow-origin: ". env('PAGSEGURO_URL', '') );
                    // $curl = curl_init();
                    // curl_setopt( $curl, CURLOPT_URL, env('PAGSEGURO_URL', '') .'pre-approvals/'. $assinatura .'/cancel/?email='. env('PAGSEGURO_EMAIL', '') .'&token='. env('PAGSEGURO_TOKEN', '') );
                    // curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
                    // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    // curl_setopt( $curl, CURLOPT_HTTPHEADER, array( "Content-Type: application/json;charset=ISO-8859-1", "Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1" ) );
                    // $response = curl_exec( $curl );
                    // curl_close( $curl );

                }
            }

        } else {

            $data['email'] = env('PAGSEGURO_EMAIL', '');
            $data['receiverEmail'] = env('PAGSEGURO_EMAIL', '');
            $data['token'] = env('PAGSEGURO_TOKEN', '');
            $data['paymentMode'] = 'default';
            $data['paymentMethod'] = 'creditCard';
            $data['currency'] = 'BRL';
            $data['extraAmount'] = '0.00';

            $data['itemId1'] = '1';
            $data['itemDescription1'] = config('app.name').' - contratacao de servico';
            $data['itemAmount1'] = $valor;
            $data['itemQuantity1'] = '1';

            $data['notificationURL'] = url('/servicos/checkout/callback');

            $data['reference'] = $pedido_id;

            $data['senderName'] = $nome;
            $data['senderCPF'] = $cpf;
            $data['senderAreaCode'] = $ddd;
            $data['senderPhone'] = $telefone;
            $data['senderEmail'] = env('PAGSEGURO_COMPRADOR_EMAIL', $email);
            $data['senderHash'] = $identificador;

            $data['shippingType'] = '3';
            $data['shippingCost'] = '0.00';
            $data['shippingAddressStreet'] = $endereco;
            $data['shippingAddressNumber'] = $numero;
            $data['shippingAddressComplement'] = $complemento;
            $data['shippingAddressDistrict'] = $bairro;
            $data['shippingAddressPostalCode'] = $cep;
            $data['shippingAddressCity'] = $cidade;
            $data['shippingAddressState'] = $uf;
            $data['shippingAddressCountry'] = 'BRA';
            $data['billingAddressStreet'] = $endereco;
            $data['billingAddressNumber'] = $numero;
            $data['billingAddressComplement'] = $complemento;
            $data['billingAddressDistrict'] = $bairro;
            $data['billingAddressPostalCode'] = $cep;
            $data['billingAddressCity'] = $cidade;
            $data['billingAddressState'] = $uf;
            $data['billingAddressCountry'] = 'BRA';

            $data['creditCardToken'] = $tokencartao;

            $data['creditCardHolderName'] = $nome;
            $data['creditCardHolderCPF'] = $cpf;
            $dt = explode('-', $data_nascimento);
            $data['creditCardHolderBirthDate'] = $dt[2].'/'.$dt[1].'/'.$dt[0];
            $data['creditCardHolderAreaCode'] = $ddd;
            $data['creditCardHolderPhone'] = $telefone;

            $data['installmentQuantity'] = $parcelamento;
            $data['installmentValue'] = $parcelaValor;
            $data['noInterestInstallmentQuantity'] = env('PAGSEGURO_NUM_PARCELAS_SEM_JUROS');

            header("access-control-allow-origin: ". env('PAGSEGURO_URL', '') );
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'));
            curl_setopt($curl, CURLOPT_URL, env('PAGSEGURO_URL', '') ."v2/transactions/" );
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $status_code = curl_getinfo($curl)['http_code'];
            curl_close($curl);

            if( $status_code >= 300 ){
                $resp = new \SimpleXMLElement( $response );
                if( isset( $resp->error ) ){
                    $erros = array();
                    foreach( $resp as $erro ){
                        $msg = $erro->code .': '. $erro->message;
                        $traducao = trans( 'pagseguro.'.$erro->code, [], 'pt-BR' );
                        if( $traducao != 'pagseguro.'.$erro->code ){
                            $msg = $erro->code .': '. $traducao;
                        }
                        $erros[] = $msg;
                    }
                    $request->erros = $erros;
                }

                if( \Auth::user() )
                    return Self::checkout( $request );
                else
                    return Self::checkout2( $request );

            }

            if( $response ){

                $resp = new \SimpleXMLElement( $response );

                if( isset( $resp->error ) ){
                    
                    $erros = array();
                    foreach( $resp as $erro ){
                        $msg = $erro->code .': '. $erro->message;
                        $traducao = trans( 'pagseguro.'.$erro->code, [], 'pt-BR' );
                        if( $traducao != 'pagseguro.'.$erro->code ){
                            $msg = $erro->code .': '. $traducao;
                        }
                        $erros[] = $msg;
                    }

                    $request->erros = $erros;

                    if( \Auth::user() )
                    return Self::checkout( $request );
                    else
                    return Self::checkout2( $request );

                } elseif( $resp->code ) {

                    $transaction_code = (String) $resp->code;
                    $pedido_id = (String) $resp->reference;

                    $status = (String) $resp->status;
                    /*
                        STATUS:
                        1 - Completo: pagamento concluído e creditado
                        2 - Aprovado: pagamento processado e aprovado
                        3 - Em Análise: pagamento sendo analisado pelo PagSeguro
                        4 - Devolvido: pagamento devolvido.
                        5 - Cancelado: transação cancelada.
                    */

                    $valorBruto = (String) $resp->grossAmount;
                    $taxa = (String) $resp->feeAmount;
                    $valorLiquido = (String) $resp->netAmount;
                    $valorExtra = (String) $resp->extraAmount;

                    $contratacao = Contratacao::find($pedido_id);
                    if( $contratacao ){

                        $contratacao->transaction_code = $transaction_code;
                        $contratacao->gorjeta = $gorjeta;
                        $contratacao->save();

                        if( $contratacao->recorrencias ) {
                            foreach( json_decode( $contratacao->recorrencias ) as $data ){
                                if( date('Y-m-d H:i:s',strtotime($data)) != $contratacao->inicio ){
                                    $new = $contratacao->replicate();
                                    $new->inicio = $data;
                                    $new->fim = date( 'Y-m-d H:i:s', strtotime( $new->inicio .'+4 hour' ));
                                    $new->save();
                                }
                            }
                        }

                    } else {
                        Payment::create([
                            'status' => $status,
                            'transaction_code' => $transaction_code,
                            'date' => date('Y-m-d H:i:s'),
                            'message' => 'Pagamento extra',
                            'valorBruto' => $valorBruto,
                            'taxa' => $taxa,
                            'valorLiquido' => $valorLiquido,
                            'valorExtra' => $valorExtra,
                            'contratacao_id' => NULL,
                            'cliente_id' => $request->cliente_id,
                        ]);
                    }

                    Session::flash('message', "Seu pagamento está sendo processado. \nEm breve, encontraremos o prestador de serviço ideal para você.");
                    if( \Auth::user() )
                    return Redirect::to('/servicos/calendario');
                    else
                    return Redirect::to('/success');

                    // return response()->json([ 
                    //     'message' => "Seu pagamento está sendo processado. \nEm breve, Encontraremos o prestador de serviço ideal para você.", 
                    //     'contratacao_id' => $contratacao->id, 
                    //     'redirectURL' => url('/servicos/calendario'), 
                    // ], 200 );

                    // return Self::notificarPrestadores( $request, (object) array( 'id' => $pedido_id ));

                }
            }
        }
    }

    public function checkoutapp( Request $request ){

        $validator = Validator::make($request->all(), [
            'pedido_id' => 'required|exists:contratacao,id',
            // 'identificador' => 'required',
            // 'sessionID' => 'required',
        ]);
        if( $validator->fails() )
            return response()->json([ 'error' => $validator->messages() ], 400 );

        $identificador = $request->identificador;
        $sessionID = $request->sessionID;
        $pedido_id = $request->pedido_id;
        $contratacao = Contratacao::find($pedido_id);
        $recorrencia = '';
        foreach( json_decode( $contratacao->campos ) as $campo ){
            if( $campo->nome == 'recorrencia' )
                $recorrencia = $campo->valor;
        }
        if( $recorrencia != 'limpeza_avulsa' and !$contratacao->recorrencias )
            return response()->json([ 'error' => 'Cadastre as recorrências dessa contratação antes de efetuar o pagamento' ], 400 );

        $validator = Validator::make($request->all(), [
            'card_number' => 'required',
            'expiration_month' => 'required|numeric|min:1|max:12',
            'expiration_year' => 'required|integer|min:'.date('Y'),
            'cvv' => 'required|numeric|max:999',
            'valor' => 'required',
        ]);
        if( $validator->fails() )
            return response()->json([ 'error' => $validator->messages() ], 400 );

        header("access-control-allow-origin: ". env('PAGSEGURO_URL', '') );
        

        if( $contratacao->transaction_code )
            return response()->json([ 'error' => [ 'transaction_code' => 'Pagamento já realizado para esse pedido.' ] ], 400 );

        $cliente = $contratacao->cliente()->first();
        if( !$cliente )
            return response()->json([ 'error' => [ 'cliente' => 'Cliente não localizado' ] ], 400 );

        $nome = Helper::sanitizeString( $cliente->nome );
        $email = $cliente->email;
        $cpf = Helper::onlyNumbers( $cliente->cpf );
        $data_nascimento = date('d/m/Y', strtotime($cliente->data_nascimento));
        $ddd = substr( Helper::onlyNumbers( $cliente->telefone ), 0, 2);
        $telefone = substr( Helper::onlyNumbers( $cliente->telefone ), 2 );
        $cep = Helper::onlyNumbers( $cliente->cep );
        $endereco = Helper::sanitizeString( $cliente->endereco );
        $numero = Helper::sanitizeString( $cliente->numero );
        $complemento = Helper::sanitizeString( $cliente->complemento );
        if(!$complemento)
            $complemento = Helper::sanitizeString( 'condominio '. $cliente->condominio .' unidade '. $cliente->unidade .' bloco '. $cliente->bloco );
        $bairro = Helper::sanitizeString( $cliente->bairro );
        $cidade = Helper::sanitizeString( $cliente->cidade );
        $uf = Helper::sanitizeString( $cliente->estado );

        $valor = Helper::formatDecimalToDb($request->valor);

        if( !$sessionID ){
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'));
            curl_setopt($curl, CURLOPT_URL, env('PAGSEGURO_URL', '') ."sessions?appId=".env('PAGSEGURO_APP_ID')."&appKey=".env('PAGSEGURO_APP_KEY') );
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $sessionID = curl_exec($curl);
            curl_close($curl);

            if( $sessionID == 'Unauthorized' )
                return response()->json([ 'error' => [ 'sessionID' => 'Serviço de pagamento temporariamente indisponível' ] ], 400 );

            // GET SESSION ID
            $resp = (Array) new \SimpleXMLElement( $sessionID );
            $sessionID = $resp['id'];
        }

        $cartao = Helper::onlyNumbers( $request->card_number );
        
        // GET BANDEIRA
        $bandeira = json_decode( file_get_contents( 'https://df.uol.com.br/df-fe/mvc/creditcard/v1/getBin?tk='. $sessionID .'&creditCard='.substr( $cartao, 0, 6 ) ) );
        if( !$bandeira->bin->brand )
            return response()->json([ 'error' => [ 'Bandeira' => 'Bandeira do cartão não identificada' ] ], 400 );

        $bandeira = $bandeira->bin->brand->name;
        
        // GET TOKEN CARTAO
        $data = array(
            'sessionId' => $sessionID,
            'amount' => $valor,
            'cardNumber' => $cartao,
            'cardBrand' => $bandeira,
            'cardCvv' => $request->cvv,
            'cardExpirationMonth' => $request->expiration_month,
            'cardExpirationYear' => $request->expiration_year,
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'));
        curl_setopt($curl, CURLOPT_URL, 'https://df.uol.com.br/v2/cards/?email='. env('PAGSEGURO_EMAIL') .'&token='. env('PAGSEGURO_TOKEN') );
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query( $data ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl)['http_code'];
        curl_close($curl);
        
        if( $status_code >= 300 ){
            return response()->json(['error' => [ 'card_token' => $response ] ], 400);
        }

        if( $response ){
            $resp = new \SimpleXMLElement( $response );
            if( isset( $resp->error ) ){
                foreach( $resp as $erro ){
                    $msg = $erro->code .': '. $erro->message;
                    $traducao = trans( 'pagseguro.'.$erro->code, [], 'pt-BR' );
                    if( $traducao != 'pagseguro.'.$erro->code ){
                        $msg = $erro->code .': '. $traducao;
                    }
                    return response()->json(['error' => [ 'card_token' => $msg ] ], 400);
                }
            } elseif( $resp->token ) {
                $tokencartao = ( (Array) $resp->token )[0];
            }
        }

        if( !$identificador ){
            $pattern = preg_quote("var senderHash = '",'/');
            $pattern = "/^.*$pattern.*\$/m";
            if( preg_match_all( $pattern, file_get_contents( 'https://pagseguro.uol.com.br/checkout/direct-payment/i-ck.html#rmcl' ), $matches ) ){
               $line = implode( "\n", $matches[0] );
               $line = str_replace( "var senderHash = '", '', $line );
               $line = str_replace( " ", '', $line );
               $identificador = str_replace( "';", '', $line );
            }
        }

        // ENVIO DE SPLIT
        $data = array();
        
        $data['currency'] = 'BRL';
        $data['reference'] = $pedido_id;
        $data['notificationURL'] = url('/servicos/checkout/callback');
        
        $data['item[1].id'] = $pedido_id;
        $data['item[1].description'] = config('app.name').' - contratacao de servico';
        $data['item[1].amount'] = $valor;
        $data['item[1].quantity'] = '1';

        $data['payment.mode'] = 'default';
        $data['payment.method'] = 'creditCard';
        
        $data['sender.name'] = $nome;
        $data['sender.CPF'] = $cpf;
        $data['sender.areaCode'] = $ddd;
        $data['sender.phone'] = $telefone;
        $data['sender.email'] = env('PAGSEGURO_COMPRADOR_EMAIL', $email);
        $data['sender.hash'] = $identificador;
        
        $data['shipping.address.street'] = $endereco;
        $data['shipping.address.number'] = $numero;
        $data['shipping.address.complement'] = $complemento;
        $data['shipping.address.district'] = $bairro;
        $data['shipping.address.postalCode'] = $cep;
        $data['shipping.address.city'] = $cidade;
        $data['shipping.address.state'] = $uf;
        $data['shipping.address.country'] = 'BRA';
        $data['shipping.type'] = '3';
        $data['shipping.cost'] = '0.00';

        $data['installment.quantity'] = '1';
        $data['installment.value'] = $valor;
        $data['installment.noInterestInstallmentQuantity'] = env('PAGSEGURO_NUM_PARCELAS_SEM_JUROS','1');

        $data['creditCard.token'] = $tokencartao;
        $data['creditCard.holder.name'] = $nome;
        $data['creditCard.holder.CPF'] = $cpf;
        $data['creditCard.holder.birthDate'] = $data_nascimento;
        $data['creditCard.holder.areaCode'] = $ddd;
        $data['creditCard.holder.phone'] = $telefone;
        
        $data['billingAddress.street'] = $endereco;
        $data['billingAddress.number'] = $numero;
        $data['billingAddress.complement'] = $complemento;
        $data['billingAddress.district'] = $bairro;
        $data['billingAddress.postalCode'] = $cep;
        $data['billingAddress.city'] = $cidade;
        $data['billingAddress.state'] = $uf;
        $data['billingAddress.country'] = 'BRA';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1', 'Accept: application/vnd.pagseguro.com.br.v3+xml'));
        curl_setopt($curl, CURLOPT_URL, env('PAGSEGURO_URL').'transactions?appId='. env('PAGSEGURO_APP_ID') .'&appKey='. env('PAGSEGURO_APP_KEY') );
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query( $data ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl)['http_code'];
        curl_close($curl);

        if( $status_code >= 300 ){
            return response()->json([ 
                'errors' => [$response, $data], 
                'contratacao_id' => $contratacao->id, 
            ], 400 );
        }

        if( $response ){

            $resp = new \SimpleXMLElement( $response );

            if( isset( $resp->error ) ){
                
                $erros = array();
                foreach( $resp as $erro ){
                    $msg = $erro->code .': '. $erro->message;
                    $traducao = trans( 'pagseguro.'.$erro->code, [], 'pt-BR' );
                    if( $traducao != 'pagseguro.'.$erro->code ){
                        $msg = $erro->code .': '. $traducao;
                    }
                    $erros[] = $msg;
                }

                return response()->json([ 
                    'errors' => $erros, 
                    'contratacao_id' => $contratacao->id, 
                ], 400 );

            } elseif( $resp->code ) {

                $transaction_code = (String) $resp->code;
                $pedido_id = (String) $resp->reference;

                $status = (String) $resp->status;
                /*
                    STATUS:
                    1 - Completo: pagamento concluído e creditado
                    2 - Aprovado: pagamento processado e aprovado
                    3 - Em Análise: pagamento sendo analisado pelo PagSeguro
                    4 - Devolvido: pagamento devolvido.
                    5 - Cancelado: transação cancelada.
                */

                $valorBruto = (String) $resp->grossAmount;
                $taxa = (String) $resp->feeAmount;
                $valorLiquido = (String) $resp->netAmount;
                $valorExtra = (String) $resp->extraAmount;

                $contratacao = Contratacao::find($pedido_id);
                $contratacao->transaction_code = $transaction_code;
                $contratacao->save();

                if( $contratacao->recorrencias ) {
                    foreach( json_decode( $contratacao->recorrencias ) as $data ){
                        if( date('Y-m-d H:i:s',strtotime($data)) != $contratacao->inicio ){
                            $new = $contratacao->replicate();
                            $new->inicio = $data;
                            $new->fim = date( 'Y-m-d H:i:s', strtotime( $new->inicio .'+4 hour' ));
                            $new->save();
                        }
                    }
                }

                return response()->json([ 
                    'message' => "Seu pagamento está sendo processado. \nEm breve, Encontraremos o prestador de serviço ideal para você.", 
                    'contratacao_id' => $contratacao->id, 
                    'redirectURL' => url('/servicos/calendario'), 
                ], 200 );

            }
        }
    }


    public function checkoutCallback( Request $request ){

        header("access-control-allow-origin: ". env('PAGSEGURO_URL'));

        Log::info('app.requests', ['request' => $request->all()]);

        if( $request->has('notificationCode') ){

            $url = env('PAGSEGURO_URL').'v2/transactions/notifications/'. $request->notificationCode .'?email='. env('PAGSEGURO_EMAIL') .'&token='. env('PAGSEGURO_TOKEN');
            $response = file_get_contents( $url );
            if( $response ){
                $resp = new \SimpleXMLElement( $response );
                Log::info('app.requests', ['response' => $response]);
                
                $payment = array();
                /* 
                    STATUS:
                    1 - Aguardando pagamento.
                    2 - Em análise.
                    3 - Paga.
                    4 - Disponível. 
                    5 - Em disputa. 
                    6 - Devolvida. 
                    7 - Cancelada.
                */
                $payment['status'] = (Integer) $resp->status;
                $payment['transaction_code'] = (String) $resp->code;
                $payment['date'] = date( 'Y-m-d H:i:s', strtotime( (String) $resp->lastEventDate ));
                $payment['message'] = (String) $resp->items->item->description;
                $payment['valorBruto'] = (String) $resp->grossAmount;
                $payment['taxa'] = (String) $resp->feeAmount;
                $payment['valorLiquido'] = (String) $resp->netAmount;
                $payment['valorExtra'] = (String) $resp->extraAmount;
                $payment['contratacao_id'] = (String) $resp->reference;

                $contratacao = Contratacao::find( $payment['contratacao_id'] );
                if( $contratacao ){
                    $cliente = $contratacao->cliente()->first();

                    $payment['cliente_id'] = $cliente->id;

                    $request->latitude = $cliente->latitude;
                    $request->longitude = $cliente->longitude;
                }

                $pagamento = Payment::where('contratacao_id',$payment['contratacao_id'])
                                ->orWhere('transaction_code',$payment['transaction_code'])
                                ->first();
                if( $pagamento ){
                    Payment::where('contratacao_id',$payment['contratacao_id'])->orWhere('transaction_code',$payment['transaction_code'])->update($payment);
                } else
                    Payment::create($payment);

                if( in_array( $payment['status'], [ 3, 4 ] ) ){
                    Self::notificarPagamentoCliente( $request, (object) array( 'id' => $payment['contratacao_id'] ))->getData();
                    Self::notificarPrestadores( $request, (object) array( 'id' => $payment['contratacao_id'] ))->getData();
                }

            }
        }
    }

    public function pagamentos( Request $request ){

        $pagamentos = Payment::orderBy('id','desc')->whereNotNull('transaction_code')->paginate(10);
        return view('servico.pagamentos',['pagamentos'=>$pagamentos]);

    }

    public function cancelarPagamento( Request $request ){

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
        ]);
        if( $validator->fails() )
            return response()->json([ 'error' => $validator->messages() ], 400 );

        $pagamento = Payment::where('transaction_code',$request->code)->first();
        if( !$pagamento )
            return response()->json([ 'error' => ['Transação não localizada.'] ], 400 );

        $valor = 0;

        // Cliente
        if( \Auth::user()->perfil_id == 2 ){

            $contratacao = $pagamento->contratacao()->first();
            $contratacao->inicio;

            $horasAteOInicoDoServico = round( abs( strtotime( $contratacao->inicio ) - strtotime( date('Y-m-d H:i:s') ) ) / 3600, 2);

            // menos de 24h do agendamento 50% devolvido
            if( $horasAteOInicoDoServico < 24 ){
                $valor = $pagamento->valorBruto - ( $pagamento->valorBruto * (50/100) );
            }

            // menos de 3h do agendamento 0% devolvido
            if( $horasAteOInicoDoServico < 3 ){
                $pagamento->status = 6;
                $pagamento->save();
                Self::notificarMudancaAgenda( $request, $contratacao, 2 )->getData();
                return response()->json([ 'message' => 'Cancelado com sucesso.' ]);
            }

        }

        // Prestador
        if( \Auth::user()->perfil_id == 3 ){

        }

        header("access-control-allow-origin: ". env('PAGSEGURO_URL', '') );

        $url = env('PAGSEGURO_URL'). 'v2/transactions/refunds?email='. env('PAGSEGURO_EMAIL') .'&token='. env('PAGSEGURO_TOKEN') .'&transactionCode='. $request->code;
        if($valor)
            $url .= '&refundValue='.$valor;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
        $response = curl_exec($ch);
        curl_close($ch);
        if( $response ){
            $resp = new \SimpleXMLElement( $response );

            if( isset( $resp->error ) ){
                    
                $repetido = false;
                $erros = array();
                foreach( $resp as $erro ){

                    $msg = $erro->code .': '. $erro->message;
                    $traducao = trans( 'pagseguro.'.$erro->code, [], 'pt-BR' );
                    if( $traducao != 'pagseguro.'.$erro->code ){
                        $msg = $erro->code .': '. $traducao;
                    }
                    $erros[] = $msg;
                    
                    if( $erro->code == 14007 )
                        $repetido = true;

                }

                if( !$repetido )
                    return response()->json([ 'error' => $erros ], 400 );

            } 

            $pagamento->status = 6;
            $pagamento->save();

            // Notificar a  mudança na agenda ( prestador e cliente )
            $contratacao = $pagamento->contratacao()->first();
            Self::notificarMudancaAgenda( $request, $contratacao, 2 )->getData();

            return response()->json([ 'message' => 'Cancelado com sucesso.' ]);

        }

        return response()->json([ 'error' => ['Sem resposta do servidor Pagseguro.'] ], 400 );
    }

    public function reagendamento( Request $request, $id ){

        $contratacao = Contratacao::findOrFail($id);
        $prestadores = Prestador::select(['prestador.*'])
                                    ->join('users', function($q){
                                        $q->on('prestador.usuario_id', '=', 'users.id')
                                          ->whereNull('users.deleted_at');
                                    })
                                    ->whereHas('servicos', function ($query) use ($contratacao) {
                                        $query->where('servico_id', $contratacao->servico_id );
                                    })
                                    ->orderBy('users.name')
                                    ->with('usuario')
                                    ->with('servicos')
                                    ->get();

        return view('servico.reagendamento',[
            'contratacao' => $contratacao,
            'prestadores' => $prestadores,
        ]);

    }

    public function reagendar( Request $request, $id ){

        $validator = Validator::make($request->all(), [
            'data' => 'required',
            // 'hora' => 'required',
            // 'prestador_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $contratacao = Contratacao::findOrFail($id);
        $prestador_id = $contratacao->prestador_id;
        if( $request->has('prestador_id') )
            $prestador_id = $request->prestador_id;

        if( is_array( $request->data ) ){
            $inicio = $request->data[0] .' '. $request->hora[0];
            $fim = $request->data[1] .' '. $request->hora[1];
        } else {
            $inicio = $request->data;
            $fim = date('Y-m-d H:i:s', strtotime($request->data.'+4 hour'));
        }

        // Comparar se fim é maior que inicio
        if( strtotime( $inicio ) > strtotime( $fim ) )
            return response()->json([ 'error' => 'Datas inválidas' ], 400 );

        // Comparar se inicio maior que hoje
        if( strtotime( $inicio ) <= strtotime( date('Y-m-d').' 23:59:59' ) )
            return response()->json([ 'error' => 'Serviço só pode ser agendado a partir de amanhã' ], 400 );

        // Verifica choque de horários do prestador
        $choqueDeHorario = Contratacao::where( 'prestador_id', $prestador_id )
                    ->where( 'cliente_id', '<>', $contratacao->cliente_id )
                    ->whereRaw( "( ( inicio BETWEEN '$inicio' AND '$fim' ) 
                               OR  ( fim BETWEEN '$inicio' AND '$fim' ) )" )
                    ->exists();

        if( $choqueDeHorario ){
            return response()->json([ 'error' => 'Choque de horário do prestador de serviço' ], 400 );
        }

        $contratacao = Contratacao::findOrFail($id)->update([
            'prestador_id' => $prestador_id,
            'inicio' => $inicio,
            'fim' => $fim,
        ]);

        // Notificar a  mudança na agenda ( prestador e cliente )
        $contratacao = Contratacao::findOrFail($id);
        Self::notificarMudancaAgenda( $request, $contratacao, 1 )->getData();

        return response()->json([ 
            'message' => 'Alterações salvas', 
            'redirectURL' => url('/pagamentos'), 
        ], 200 );

    }

    public function testPush( Request $request ){

        $contratacao = Contratacao::findOrFail( $request->contratacao_id );

        $recipients = array();
        $recipients[] = $request->token;

        if( is_array( $request->token ) )
            $recipients = $request->token;

        $recipients = array_unique($recipients);

        // return Self::notificarPrestadores( $request, (object) array( 'id' => $request->contratacao_id ));

        $cliente = $contratacao->cliente()->with('usuario')->first();
        $servico = $contratacao->servico()->first();
        $prestador = $contratacao->prestador()->with('usuario')->first();

        // $tipoNotificacao = 'prestadores';
        // $tipoNotificacao = 'pagamentoConfirmado';
        $tipoNotificacao = 'servicoAceito';
        // $tipoNotificacao = 'mudancaAgenda';

        // $tipo = 0; // 'Houveram Mudanças na Agenda';
        $tipo = 1; // 'Serviço aceito pelo prestador ';
        // $tipo = 2; // 'Serviço cancelado pelo cliente';
        // $tipo = 3; // 'Serviço cancelado pelo prestador';

        if( $tipoNotificacao == 'prestadores' ){
            $notificacao = fcm()->to($recipients)
                        ->priority('high')
                        ->timeToLive(0)
                        ->notification([
                            'type' => "push::type::notify",
                            'priority' => 'high',
                            'show_in_foreground' => true,
                            'show_foreground' => true,
                            // 'content_available' => true,
                            // 'content-available' => true,
                            'sound' => 'default',
                            'title' => 'Novo serviço disponível em sua área!',
                            'body' => 'Mensagem broadcast para aceitar serviço',
                            'contratacao_id' => $contratacao->id,
                            // 'contratacao' => $contratacao,
                        ])->data([
                            'type' => "push::type::notify",
                            'priority' => 'high',
                            'show_in_foreground' => true,
                            'show_foreground' => true,
                            // 'content_available' => true,
                            // 'content-available' => true,
                            'sound' => 'default',
                            'title' => 'Novo serviço disponível em sua área!',
                            'body' => 'Mensagem broadcast para aceitar serviço',
                            'contratacao_id' => $contratacao->id,
                            // 'contratacao' => $contratacao,
                        ])->send();
        } elseif( $tipoNotificacao == 'pagamentoConfirmado' ){

            $notificacao = fcm()->to($recipients)
            ->priority('high')
            ->timeToLive(0)
            ->notification([
                'type' => "push::type::notify",
                'priority' => 'high',
                'show_in_foreground' => true,
                'show_foreground' => true,
                // 'content_available' => true,
                // 'content-available' => true,
                'sound' => 'default',
                'title' => 'Pagamento confirmado',
                'body' => 'Pagamento confirmado',
                'contratacao_id' => $contratacao->id,
                // 'contratacao' => $contratacao,
                // 'servico' => $servico,
            ])->data([
                'type' => "push::type::notify",
                'priority' => 'high',
                'show_in_foreground' => true,
                'show_foreground' => true,
                // 'content_available' => true,
                // 'content-available' => true,
                'sound' => 'default',
                'title' => 'Pagamento confirmado',
                'body' => 'Pagamento confirmado',
                'contratacao_id' => $contratacao->id,
                // 'contratacao' => $contratacao,
                // 'servico' => $servico,
            ])->send();
        } elseif( $tipoNotificacao == 'servicoAceito' ){

            $notificacao = fcm()->to($recipients)
            ->priority('high')
            ->timeToLive(0)
            ->notification([
                'type' => "push::type::notify",
                'priority' => 'high',
                'show_in_foreground' => true,
                'show_foreground' => true,
                // 'content_available' => true,
                // 'content-available' => true,
                'sound' => 'default',
                'title' => 'Serviço aceito',
                'body' => $contratacao->inicio .': '. $servico->nome .' - '. $prestador->usuario->nome,
                'contratacao_id' => $contratacao->id,
                // 'contratacao' => $contratacao,
                // 'prestador' => $prestador,
                // 'cliente' => $cliente,
            ])->data([
                'type' => "push::type::notify",
                'priority' => 'high',
                'show_in_foreground' => true,
                'show_foreground' => true,
                // 'content_available' => true,
                // 'content-available' => true,
                'sound' => 'default',
                'title' => 'Serviço aceito',
                'body' => $contratacao->inicio .': '. $servico->nome .' - '. $prestador->usuario->nome,
                'contratacao_id' => $contratacao->id,
                // 'contratacao' => $contratacao,
                // 'prestador' => $prestador,
                // 'cliente' => $cliente,
            ])->send();
        } elseif( $tipoNotificacao == 'mudancaAgenda' ){

            switch( $tipo ){
                case 1:
                    $title = 'Serviço aceito pelo prestador '. $prestador->usuario->name;
                    $body = 'Acesse seu calendário para mais detalhes';
                break;
                case 2:
                    $title = 'Serviço cancelado pelo cliente';
                    $body = 'Acesse seu calendário para mais detalhes';
                break;
                case 3:
                    $title = 'Serviço cancelado pelo prestador';
                    $body = 'Estamos procurando outro prestador de serviço para sua demanda.';
                break;
                default:
                    $title = 'Houveram Mudanças na Agenda';
                    $body = 'Acesse seu calendário para mais detalhes';
                break;
            }
            $notificacao = fcm()->to($recipients)
                            ->priority('high')
                            ->timeToLive(0)
                            ->notification([
                                'type' => "push::type::notify",
                                'priority' => 'high',
                                'show_in_foreground' => true,
                                'show_foreground' => true,
                                // 'content_available' => true,
                                // 'content-available' => true,
                                'sound' => 'default',
                                'title' => $title,
                                'body' => $body,
                                'contratacao_id' => $contratacao->id,
                                // 'contratacao' => $contratacao,
                                // 'prestador' => $prestador,
                                // 'cliente' => $cliente,
                            ])->data([
                                'type' => "push::type::notify",
                                'priority' => 'high',
                                'show_in_foreground' => true,
                                'show_foreground' => true,
                                // 'content_available' => true,
                                // 'content-available' => true,
                                'sound' => 'default',
                                'title' => $title,
                                'body' => $body,
                                'contratacao_id' => $contratacao->id,
                                // 'contratacao' => $contratacao,
                                // 'prestador' => $prestador,
                                // 'cliente' => $cliente,
                            ])->send();
        }

        return response()->json([ 'retorno' => $notificacao ], 200 );

    }
    
    public function cancelarServico( Request $request, $contratacao_id ){
        
        $contratacao = Contratacao::findOrFail( $contratacao_id );

        if( $contratacao->iniciado )
            return response()->json([ 'message' => 'Serviço já iniciado!' ], 400);

        $prestador = Prestador::where( 'usuario_id', \Auth::user()->id )->first();
        if( $prestador ){
           
            if( $prestador->id != $contratacao->prestador_id )
                return response()->json([ 'error' => 'Você não é o prestador desse serviço' ], 400 );

            $cancelamentos = (Array) json_decode( $contratacao->cancelamento_prestador );
            if( ! in_array( $contratacao->prestador_id, $cancelamentos ) ){
                $cancelamentos[] = $contratacao->prestador_id;
                $contratacao->cancelamento_prestador = json_encode( $cancelamentos );
                $contratacao->prestador_id = null;
                $contratacao->save();
            }
            
            Self::notificarPrestadores( $request, (Object) array( 'id' => $contratacao_id ) )->getData();
            Self::notificarMudancaAgenda( $request, $contratacao, 3)->getData();

            return response()->json([ 'error' => 'Serviço cancelado, você não será mais avisado sobre esse serviço.' ], 200 );

        } else {
            
            // CLIENTE
            $request->merge(["code"=>$contratacao->transaction_code]);
            return Self::cancelarPagamento( $request );

        }

    }
    
    public function recusarServico( Request $request, $contratacao_id ){
        
        $contratacao = Contratacao::findOrFail( $contratacao_id );

        if( $contratacao->iniciado )
            return response()->json([ 'message' => 'Serviço já iniciado!' ], 400);

        $prestador = Prestador::where( 'usuario_id', \Auth::user()->id )->first();
        if(!$prestador)
            return response()->json([ 'error' => 'Você não é prestador de serviço' ], 400 );

        $cancelamentos = (Array) json_decode( $contratacao->cancelamento_prestador );
        if( ! in_array( \Auth::user()->id, $cancelamentos ) ){
            $cancelamentos[] = \Auth::user()->id;
            $contratacao->cancelamento_prestador = json_encode( $cancelamentos );
            $contratacao->save();
        }
        
        return response()->json([ 'error' => 'Serviço recusado, você não será mais avisado sobre esse serviço.' ], 200 );

    }

    public function feedbackServico( Request $request, $contratacao_id ){


        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'justificativa' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $contratacao = Contratacao::findOrFail( $contratacao_id );

        $cliente = Cliente::where( 'usuario_id', \Auth::user()->id )->first();
        if( !$cliente || \Auth::user()->perfil_id != 2 )
            return response()->json([ 'error' => 'Seu usuario não é cliente' ], 400 );

        if( $cliente->id != $contratacao->cliente_id )
            return response()->json([ 'error' => 'Você não é o cliente desse serviço' ], 400 );

        if( !$contratacao->concluido )
            return response()->json([ 'error' => 'A contração ainda não foi finalizada pelo prestador' ], 400 );

        $justificativa = '';
        if( $request->has('justificativa') )
            $justificativa = $request->justificativa;

        $contratacao->rating = intval($request->rating);
        $contratacao->justificativa = $justificativa;
        $contratacao->save();

        return response()->json([ 'error' => 'Obrigado pelo seu feedback, isso nos ajudará a melhorar nossos serviços' ], 200 );

    }

}
