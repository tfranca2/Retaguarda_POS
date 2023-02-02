<?php

namespace App\Http\Controllers;

use Helper;
use Session;
use App\User;
use App\Venda;
use App\Etapa;
use Validator;
use App\Cidade;
use App\Estado;
use App\Matriz;
use App\Payment;
use Carbon\Carbon;
use App\Helpers\Pix;
use App\VendaMatriz;
use App\Dispositivo;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

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
                $vendas->where('vendas.created_at','>',$request->inicio.' 00:00:00');

        if( $request->has('fim') )
            if( $request->input('fim') )
                $vendas->where('vendas.created_at','<',$request->fim.' 23:59:59');

        if( $request->has('cpf') )
            if( $request->input('cpf') )
                $vendas->where('cpf','=', Helper::onlyNumbers($request->cpf));

        if( $request->has('tipo') )
            if( $request->input('tipo') )
                $vendas->whereHas('pagamento', function ($query) use ($request) {
                    $query->where('tipo', $request->input('tipo') );
                });

        if( $request->has('bilhete') )
            if( Helper::onlyNumbers( $request->input('bilhete') ) )
                $vendas->whereHas('venda_matriz', function ($query) use ($request) {
                    $query->where('matriz_id', Helper::onlyNumbers( $request->input('bilhete') ) );
                });

        return $vendas->orderBy('vendas.created_at','DESC')
                    ->with('etapa')
                    ->with('dispositivo');
    }

    public function getAll( Request $request ){
        return response()->json( Self::filter( $request )->paginate(10), 200 );
    }

    public function index( Request $request ){

        if( ! Etapa::ativa() ){
            Session::flash('error', "Não existe etapa ativa!");
            return redirect('/etapas');
        }

        $vendas = Self::filter( $request )
        ->select('vendas.*', 'payments.venda_id', 'payments.tipo', 'payments.status')
        ->whereNotNull('cpf')
        ->join('payments', 'vendas.id', '=', 'payments.venda_id');
        $totalVendas = 0;
        $totalComissao = 0;
        foreach( $vendas->get() as $venda ){
            $totalVendas += $venda->etapa->valor;
            $totalComissao += $venda->etapa->comissao;
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

        $tipos = Payment::distinct()->select('tipo')->pluck('tipo')->toArray();
        sort($tipos);

        return view('venda.index',[ 'vendas' => $vendas->paginate(10), 'etapas' => $etapas, 'distribuidores' => $distribuidores, 'dispositivo' => $dispositivo, 'totalVendas' => $totalVendas, 'totalComissao' => $totalComissao, 'tipos' => $tipos, ]);
    }

    public function leads( Request $request ){

        $etapas = Etapa::orderBy('id','DESC')->get();

        $vendas = Self::filter( $request )->where('confirmada', 1)
                ->join('payments','payments.venda_id','=','vendas.id')
                ->get()
                ->pluck('venda_id')
                ->toArray();

        $leads = Venda::withTrashed()->whereNotNull('cpf')
                ->whereNotIn('id', $vendas)
                ->where('etapa_id', $request->etapa_id)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);

        return view('venda.leads',[ 'etapas' => $etapas, 'leads' => $leads ]);
    }

    public function csv( Request $request ){

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; charset=UTF-8; filename=". date('YmdHis') ."_vendas.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $vendas = Self::filter( $request )->where('confirmada', 1)->get();

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

                $totalVendas += $venda->etapa->valor;
                $totalComissao += $venda->etapa->comissao;

                $dispositivo = '';
                $distribuidor = $venda->pdv;
                if( $venda->dispositivo ){
                    $dispositivo = $venda->dispositivo->nome;
                    $distribuidor = $venda->dispositivo->distribuidor->nome;
                }

                fputcsv( $file, [ 
                    utf8_decode( $venda->etapa->descricao ), 
                    utf8_decode( $distribuidor ), 
                    utf8_decode( $dispositivo ), 
                    Helper::formatDecimalToView( $venda->etapa->valor ), 
                    Helper::formatDecimalToView( $venda->etapa->comissao ), 
                    $venda->created_at, 
                ], ';', '"', "\n" );
            }

            fputcsv($file, [ '', '', '', Helper::formatDecimalToView( $totalVendas ), Helper::formatDecimalToView( $totalComissao ), '' ], ';', '"', "\n");
            fclose($file);
        };

        return \Response::stream( $callback, 200, $headers );
    }

    public function leadscsv( Request $request ){

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; charset=UTF-8; filename=". date('YmdHis') ."_leads.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $vendas = Self::filter( $request )->where('confirmada', 1)
                ->join('payments','payments.venda_id','=','vendas.id')
                ->get()
                ->pluck('venda_id')
                ->toArray();

        $leads = Venda::withTrashed()->whereNotNull('cpf')
                ->whereNotIn('id', $vendas)
                ->where('etapa_id', $request->etapa_id)
                ->orderBy('created_at', 'DESC')
                ->get();

        $callback = function() use ( $leads ){
            $file = fopen('php://output', 'w');
            fputcsv($file, [ 'CPF', 'Nome', 'Telefone', 'E-mail', 'Data' ], ';', '"', "\n");
            foreach( $leads as $lead ){
                fputcsv( $file, [ 
                    Helper::formatCpfCnpj( $lead->cpf ),
                    utf8_decode( $lead->nome ),
                    Helper::formatTelefone( $lead->telefone ),
                    utf8_decode( $lead->email ),
                    $lead->created_at, 
                ], ';', '"', "\n" );
            }
            fclose($file);
        };

        return \Response::stream( $callback, 200, $headers );
    }

    public function updateLead( Request $request ){
        $venda = Venda::where( 'key', $request->key )->first();
        if( $venda ){

            if( $request->has('cpf') and $request->cpf )
                $venda->cpf = Helper::onlyNumbers( $request->cpf );
            if( $request->has('nome') and $request->nome )
                $venda->nome = $request->nome;
            if( $request->has('email') and $request->email )
                $venda->email = $request->email;
            if( $request->has('telefone') and $request->telefone )
                $venda->telefone = Helper::onlyNumbers( $request->telefone );
            
            $cidade_id = env('CIDADE_ID_PADRAO', null);
            if( $request->has('uf') and $request->uf ){
                $estado = Estado::where('uf', $request->uf)->first();
                if( $estado and $request->has('cidade') and $request->cidade ){
                    $cidade = Cidade::where('estado_id', $estado->id)
                            ->where('nome', $request->cidade)->first();
                    if( $cidade )
                        $cidade_id = $cidade->id;
                }
            }
            $venda->cidade_id = $cidade_id;

            $venda->save();
        }
        return response([]);
    }

    public function txt( Request $request ){

        if( $request->has('etapa_id') ){
            $etapa =  Etapa::find( $request->etapa_id );
        } else {
            $etapa = Etapa::ativa();
        }

        $headers = array(
            "Content-type" => "text/plain",
            "Content-Disposition" => "attachment; charset=UTF-8; filename=". env('TXT_EXPORT_NAME') ."-edicao-". $etapa->etapa .".txt",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $vendas = Venda::where('etapa_id', $etapa->id)->where('confirmada',1)->get();

        $callback = function() use ( $vendas, $etapa ){

            $totalVendas = 0;
            $range_final = 0;
            $range_inicial = 0;

            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'H', // cabeçalho fixo
                env('TXT_EXPORT_OPERACAO'), // nome da operacao
                date('d/m/Y', strtotime($etapa->data)), // data sorteio
                date('d/m/Y'), // data geracao
                env('TXT_EXPORT_DISTRIBUIDOR'), // numero do distribuidor
            ], ';', chr(32), "\n");
            foreach( $vendas as $venda ){

                $matrizes = $venda->matrizes();

                $cidade_nome = '';
                $uf = '';
                $cidade = Cidade::find($venda->cidade_id);
                if( $cidade ){
                    $cidade_nome = strtoupper(Helper::sanitizeString($cidade->nome));
                    $estado = Estado::find($cidade->estado_id);
                    if( $estado )
                        $uf = strtoupper($estado->uf);
                }

                if( isset($matrizes) and isset($matrizes[0]) ){

                    $nome_do_comprador = $venda->nome;
                    if( !$nome_do_comprador ){
                        try {
                            if( env('CONSULTA_CPF', false) ){
                                $pessoa = \DB::connection('mysql2')->select("SELECT nome FROM cadcpf WHERE CPF = '". Helper::onlyNumbers($venda->cpf) ."'");
                                if($pessoa){
                                    $nome_do_comprador = $pessoa[0]->nome;
                                    
                                    $vnd = Venda::find( $venda->id );
                                    $vnd->nome = $nome_do_comprador;
                                    $vnd->save();

                                }
                            }
                        } catch( Exception $e ){

                        }

                        if( !$nome_do_comprador ){
                            $nome_do_comprador = 'Cliente não identificado';
                        }
                    }
                    $nome_do_comprador = strtoupper(Helper::sanitizeString($nome_do_comprador));

                    $bilhete = $matrizes[0]['matriz']['bilhete'];

                    $totalVendas++;
                    if( $bilhete > $range_final )
                        $range_final = $bilhete;

                    if( !$range_inicial )
                        $range_inicial = $bilhete;

                    fputcsv( $file, [ 
                        'D3', // cabeçalho fixo
                        $bilhete, // numero do titulo
                        Helper::formatDecimalToDb( $venda->etapa->valor ), // valor de venda
                        Helper::onlyNumbers($venda->cpf), // cpf
                        $nome_do_comprador, // nome comprador
                        '', // data nascimento
                        '', // sexo
                        '', // email
                        substr(Helper::onlyNumbers($venda->telefone), 0, 2), // ddd
                        substr(Helper::onlyNumbers($venda->telefone), 2), // telefone
                        $uf, // uf
                        Helper::onlyNumbers($venda->cep), // cep
                        $cidade_nome, // cidade
                        '', // dados livres
                        '', // dados livres
                        '', // dados livres
                        '', // dados livres
                        '', // dados livres
                        'V', // V - venda | C - cadastro
                        '', // dados livres
                        '', // dados livres
                    ], ';', chr(32), "\n" );
                }
            }

            fputcsv($file, [
                'T', // cabeçalho fixo
                $totalVendas, // quantidade de linhas D3
                $range_inicial, // inicial do range de vendas deste arquivo
                $range_final, // final do range
            ], ';', chr(32), "\n");

            fclose($file);
        };

        return \Response::stream( $callback, 200, $headers );
    }

    public function create( Request $request ){
        $dispositivos = Dispositivo::get();

        $frequencia = 'semanal';
        if( $request->has('frequencia') and $request->frequencia == 'mensal' )
            $frequencia = 'mensal';

        $etapa = Etapa::ativa( $frequencia );
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

        $frequencia = 'semanal';
        if( $request->has('frequencia') and $request->frequencia == 'mensal' )
            $frequencia = 'mensal';

        $etapa = Etapa::ativa( $frequencia );
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

        $nome = null;
        if( $request->has('nome') ){
        //     if( ! Helper::validaNome($request->nome) )
        //         return response()->json(['error'=>['nome'=>['Informe o nome completo.']]],400);
            $nome = $request->nome;
        }

        if( $request->has('cpf') and !$nome ){
            if( ! Helper::validaCPF($request->cpf) )
                return response()->json(['error'=>['cpf'=>['Informe um cpf válido.']]],400);
            try {
                if( env('CONSULTA_CPF', false) ){
                    $pessoa = \DB::connection('mysql2')->select("SELECT nome FROM cadcpf WHERE CPF = '". Helper::onlyNumbers($request->cpf) ."'");
                    if($pessoa){
                        $nome = $pessoa[0]->nome;
                    }
                }
            } catch( Exception $e ){

            }

            if( !$nome )
                $nome = 'Cliente não identificado';
        }

        if( $request->has('telefone') )
            if( ! Helper::validaCelular($request->telefone) )
                return response()->json(['error'=>['telefone'=>['Informe um telefone válido.']]],400);

        $cidade_id = env('CIDADE_ID_PADRAO', null);
        $cep = null;
        if( $request->has('cep') ){
            if( ! Helper::validaCep($request->cep) )
                return response()->json(['error'=>['cep'=>['Informe um cep válido.']]],400);

            $cep = Helper::onlyNumbers( $request->cep );
            $validaCEP = json_decode( file_get_contents("https://viacep.com.br/ws/".$cep."/json/" ) );
            if( ! isset($validaCEP->erro) ){
                if( isset($validaCEP->localidade) and isset($validaCEP->uf) ){
                    $estado = Estado::where('uf', $validaCEP->uf)->first();
                    if( $estado ){
                        $cidade = Cidade::where('estado_id', $estado->id)
                                ->where('nome', $validaCEP->localidade)->first();
                        if( $cidade )
                            $cidade_id = $cidade->id;
                    }
                }
            }

        }

        $qtd = 1;
        if( $etapa->tipo == 1)
            $qtd = 1;
        elseif( $etapa->tipo == 2)
            $qtd = 2;
        elseif( $etapa->tipo == 3)
            $qtd = 3;
        elseif( $etapa->tipo == 4 && $request->has('quantidade') && in_array( $request->quantidade, [ 1, 2 ] ) )
            $qtd = $request->quantidade;
        elseif( $etapa->tipo == 5 && $request->has('quantidade') && in_array( $request->quantidade, [ 1, 2, 3 ] ) )
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
            $venda['nome'] = $nome;
            $venda['cep'] = $cep;
            $venda['cidade_id'] = $cidade_id;
            $venda['ip'] = $request->ip();
            $venda['etapa_id'] = $etapa->id;
            if( $dispositivo )
                $venda['dispositivo_id'] = $dispositivo->id;

            $venda['ceder_resgate'] = 1;
            if( $request->has('ceder_resgate') )
                $venda['ceder_resgate'] = $request->ceder_resgate;

            $venda['key'] = Str::uuid();
            $venda['matriz'] = env('MATRIZ', 'matrizes');
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
                            ])->whereNotIn( 'id', function($query) use ($etapa) {
                                $query->select('matriz_id')
                                ->distinct()
                                ->from( with( new VendaMatriz )->getTable() )
                                ->join( 'vendas', 'vendas.id', '=', 'venda_id' )
                                ->whereNotNull( 'matriz_id' )
                                ->where( 'etapa_id', $etapa->id )
                                ->get()
                                ->pluck( 'matriz_id' )
                                ->toArray();
                            })
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

            $venda->pagamento()->create([
                'venda_id' => $venda->id,
                'tipo' => 'POS',
                'status' => 'WAITING',
                'valor_bruto' => $venda->etapa->valor,
            ]);

            $venda = Venda::with('etapa')->find( $venda->id );
            $venda->matrizes = $venda->matrizes();
            $etapa = Etapa::find( $venda->etapa->id );
            $venda->premiacao = $etapa->premiacao;
            $venda->premiacaoEletronica = $etapa->premiacaoEletronica;

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
    
    public function show( Request $request, $key ){
        try {
            $venda = Venda::with('etapa')->where('key', $key)->first();
            $venda->matrizes = $venda->matrizes();
            $etapa = Etapa::find( $venda->etapa->id );
            $venda->premiacao = $etapa->premiacao;
            $venda->premiacaoEletronica = $etapa->premiacaoEletronica;
            return response()->json( $venda );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404);
        }
    }
    
    public function edit( Request $request, $id ){
        $dispositivos = Dispositivo::get();
        $venda = Venda::find($id);
        $venda->matrizes = $venda->matrizes();
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

        $venda->pagamento()->update([ 'status' => 'PAID' ]);

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
        $venda = Venda::with('etapa')->where('key',$key)->first();
        if( $venda ){
            
            if( !$venda->pagamento or  in_array( $venda->pagamento->status, [ 'WAITING', 'IN_ANALYSIS' ] ) )
                $venda->confirmada = false;

            $venda->matrizes = $venda->matrizes();
            return view('venda.comprovante',[ 'venda' => $venda ]);
        }
        abort(404);
    }

    public function correios( Request $request){
        $validators = [
            'codigoCorreios' => 'required|string',
        ];
        $validator = Validator::make($request->all(),$validators);
        if( $validator->fails() )
            return response()->json(['error'=>$validator->messages()],400);
        
        $frequencia = 'semanal';
        if( $request->has('frequencia') and $request->frequencia == 'mensal' )
            $frequencia = 'mensal';

        $etapa = Etapa::ativa( $frequencia );
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

        $frequencia = 'semanal';
        if( $request->has('frequencia') and $request->frequencia == 'mensal' )
            $frequencia = 'mensal';

        $etapa = Etapa::ativa( $frequencia );
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
        if( $etapa->tipo == 1)
            $qtd = 1;
        elseif( $etapa->tipo == 2)
            $qtd = 2;
        elseif( $etapa->tipo == 3)
            $qtd = 3;
        elseif( $etapa->tipo == 4 && $request->has('quantidade') && in_array( $request->quantidade, [ 1, 2 ] ) )
            $qtd = $request->quantidade;
        elseif( $etapa->tipo == 5 && $request->has('quantidade') && in_array( $request->quantidade, [ 1, 2, 3 ] ) )
            $qtd = $request->quantidade;
        
        \DB::beginTransaction();
        try {

            $venda = Input::except( 'id', '_method', '_token', 'quantidade' );
            $venda['ip'] = $request->ip();
            $venda['etapa_id'] = $etapa->id;
            $venda['ceder_resgate'] = 1;
            $venda['pdv'] = 'correios';
            $venda['key'] = Str::random(10).time();
            $venda['matriz'] = env('MATRIZ', 'matrizes');
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
                            ->whereNotIn( 'id', function($query) use ($etapa) {
                                $query->select('matriz_id')
                                ->distinct()
                                ->from( with( new VendaMatriz )->getTable() )
                                ->join( 'vendas', 'vendas.id', '=', 'venda_id' )
                                ->whereNotNull( 'matriz_id' )
                                ->where( 'etapa_id', $etapa->id )
                                ->get()
                                ->pluck( 'matriz_id' )
                                ->toArray();
                            })
                            ->first()
                            ->id;
                VendaMatriz::create([ 
                    'venda_id' => $venda->id, 
                    'matriz_id' => $matriz_id 
                ]);

            }

            $venda = Venda::find( $venda->id );
            $venda->pagamento()->create([
                'venda_id' => $venda->id,
                'tipo' => 'API',
                'status' => 'WAITING',
                'valor_bruto' => $venda->etapa->valor,
            ]);

            $matrizes = "";
            foreach( $venda->matrizes() as $matriz ){
                $chunk = explode( '-', $matriz['matriz']['combinacoes'] );
                foreach( $chunk as $k => $c ){
                    $matrizes .= $c.' ';
                    if( in_array( $k, [ 9, 19 ] ) )
                        $matrizes .= '+';
                }
            }

            // impressão da premiação
            $matrizes .= '+---------------------------------------------++'.
                         'Sorteio '. $venda->etapa->etapa .' - '. $venda->etapa->descricao .
                         ' ( '. date('d/m/Y', strtotime($venda->etapa->data)) .' ) ++';
            foreach( $venda->etapa->premiacao as $premio ){
                $matrizes .= $premio->seq .'º Prêmio: '. $premio->descricao 
                          .' - R$ '. Helper::formatDecimalToView($premio->liquido) .' +';
            }
            $matrizes .= '+e mais '. $venda->etapa->premiacaoEletronica->count() .' prêmios de R$ '. Helper::formatDecimalToView($venda->etapa->premiacaoEletronica[0]->liquido) .'++';

            // chamar a api correios para registrar atendimento
            $data = [
                'codigoCorreios' => $request->codigoCorreios,
                'valorServico' => $venda->etapa->valor,
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
                'message' => 'Criado com sucesso',
                'redirectURL' => url('/comprovante/'.$venda->key),
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

            $venda->pagamento()->update([ 'status' => 'PAID' ]);

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
    
    public function prevenda( Request $request ){

        $frequencia = 'semanal';
        if( $request->has('frequencia') and $request->frequencia == 'mensal' )
            $frequencia = 'mensal';

        $etapa = Etapa::ativa( $frequencia );
        if( !$etapa )
            return response()->json(['error'=>['etapa'=>['Etapa não localizada.']]],400);

        // etapa passou do prazo
        if( strtotime( $etapa->data.' 23:59:59' ) < strtotime( date('Y-m-d H:i:s') ) )
            return response()->json(['error'=>['etapa'=>['Etapa inválida.']]],400);

        $qtd = 1;
        if( $etapa->tipo == 1)
            $qtd = 1;
        elseif( $etapa->tipo == 2)
            $qtd = 2;
        elseif( $etapa->tipo == 3)
            $qtd = 3;
        elseif( $etapa->tipo == 4 && $request->has('quantidade') && in_array( $request->quantidade, [ 1, 2 ] ) )
            $qtd = $request->quantidade;
        elseif( $etapa->tipo == 5 && $request->has('quantidade') && in_array( $request->quantidade, [ 1, 2, 3 ] ) )
            $qtd = $request->quantidade;
        
        \DB::beginTransaction();
        try {

            $venda = Input::except( 'id', '_method', '_token', 'quantidade' );
            $venda['ip'] = $request->ip();
            $venda['etapa_id'] = $etapa->id;
            $venda['ceder_resgate'] = 1;
            $venda['key'] = Str::uuid();
            $venda['matriz'] = env('MATRIZ', 'matrizes');
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
                            ])->whereNotIn( 'id', function($query) use ($etapa) {
                                $query->select('matriz_id')
                                ->distinct()
                                ->from( with( new VendaMatriz )->getTable() )
                                ->join( 'vendas', 'vendas.id', '=', 'venda_id' )
                                ->whereNotNull( 'matriz_id' )
                                ->where( 'etapa_id', $etapa->id )
                                ->get()
                                ->pluck( 'matriz_id' )
                                ->toArray();
                            })
                            ->first()
                            ->id;

                VendaMatriz::create([ 
                    'venda_id' => $venda->id, 
                    'matriz_id' => $matriz_id 
                ]);

            }

            $venda = Venda::with('etapa')->find( $venda->id );
            $matrizes = $venda->matrizes();

            $cartelas = [];
            foreach( $matrizes as $matriz ){
                $cartelas[] = [ 
                    'bilhete' => $matriz['matriz']['bilhete'],
                    'combinacoes' => $matriz['matriz']['combinacoes'],
                ];
            }

            \DB::commit();
            return response()->json([
                'valor' => Helper::formatDecimalToView( $venda->etapa->valor ),
                'key' => $venda->key,
                'cartelas' => $cartelas,
            ],201);
        } catch( \Exception $e ){
            \DB::rollback();
            return response()->json(['error'=>$e->getMessage()],404);
        }
    }

    public function cartela( Request $request ){
        $estados = Estado::all();

        $erros = null;
        if( isset($request->erros) and $request->erros )
            $erros = $request->erros;

        session()->forget('timeleft');

        return view('venda.prevenda', [ 'estados' => $estados, 'erros' => $erros ]);
    }

    public function prevendaconfirma( Request $request ){

        $requestCaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=". env('RECAPTCHA_SECRET_KEY') ."&response=". $request->g_recaptcha_response ."&remoteip=". $_SERVER['REMOTE_ADDR']);
        $responseCaptcha = json_decode($requestCaptcha);
        if( !$request->g_recaptcha_response or !$responseCaptcha or $responseCaptcha->success === false ){
            return response()->json([ 'error' => 'Captcha falhou! Tente novamente.' ], 400 );
        }
        $request->request->remove('g_recaptcha_response');

        $validators = [
            'key' => 'required|max:36|exists:vendas,key',
            'cpf' => 'required|max:14',
            'telefone' => 'required|max:16',
            'email' => 'required|email|max:100',
            'cidade' => 'required|max:50',
            'uf' => 'required|string|max:2|exists:estados,uf',
        ];

        $validator = Validator::make($request->all(),$validators);
        if( $validator->fails() )
            return response()->json(['error'=>$validator->messages()],400);

        $venda = Venda::where( 'key', $request->key )->first();

        $nome = null;
        if( $request->has('nome') ){
            $nome = $request->nome;
        }

        if( $request->has('cpf') and !$nome ){
            if( ! Helper::validaCPF($request->cpf) )
                return response()->json(['error'=>['cpf'=>['Informe um cpf válido.']]],400);
            try {
                if( env('CONSULTA_CPF', false) ){
                    $pessoa = \DB::connection('mysql2')->select("SELECT nome FROM cadcpf WHERE CPF = '". Helper::onlyNumbers($request->cpf) ."'");
                    if($pessoa){
                        $nome = $pessoa[0]->nome;
                    }
                }
            } catch( Exception $e ){

            }

            if( !$nome )
                $nome = 'Cliente não identificado';

            $request->merge([ 'cpf' => Helper::onlyNumbers($request->cpf) ]);
        }

        $request->merge([ 'nome' => $nome ]);

        if( $request->has('telefone') ){
            if( ! Helper::validaCelular($request->telefone) )
                return response()->json(['error'=>['telefone'=>['Informe um telefone válido.']]],400);

            $request->merge([ 'telefone' => Helper::onlyNumbers($request->telefone) ]);
        }

        $cidade_id = env('CIDADE_ID_PADRAO', null);
        $cep = null;
        try{
            $estado = Estado::where('uf', $request->uf)->first();
            if( $estado ){
                $cidade = Cidade::where('estado_id', $estado->id)
                        ->where('nome', $request->localidade)->first();
                if( $cidade )
                    $cidade_id = $cidade->id;
            }
            $validaCEP = json_decode( file_get_contents("https://viacep.com.br/ws/". $request->uf ."/". $request->cidade ."/centro/json/" ) );
            if( !isset($validaCEP->erro) ){
                if( isset($validaCEP->cep) ){
                    $cep = Helper::onlyNumbers( $validaCEP->cep );
                }
            }
        } catch( \Exception $e ){}

        $request->merge([ 'cep' => $cep ]);
        $request->merge([ 'cidade_id' => $cidade_id ]);

        $request->request->remove('cidade');
        $request->request->remove('uf');

        session([ 'timeleft' => $request->timeleft ]);
        $request->request->remove('timeleft');

        Self::update( $request, $venda->id )->getData();
        return response()->json([ 'redirectURL' => url('/checkout/'.$venda->key) ], 200 );
    }

    public function checkout( Request $request, $key ){
       
        $sessionID = '';
        $erros = null;

        $frequencia = 'semanal';
        if( $request->has('frequencia') and $request->frequencia == 'mensal' )
            $frequencia = 'mensal';

        $etapa = Etapa::ativa( $frequencia );
        $venda = Venda::where( 'key', $key )->first();
        if( !$venda )
            $erros[] = 'Chave não localizada';

        if( isset($request->erros) and $request->erros )
            $erros = $request->erros;

        if( isset($venda->pagamento) )
            return redirect('comprovante/'.$venda->key);

        $cidade_nome = '';
        $uf = '';
        $cidade = Cidade::find($venda->cidade_id);
        if( $cidade ){
            $cidade_nome = strtoupper(Helper::sanitizeString($cidade->nome));
            $estado = Estado::find($cidade->estado_id);
            if( $estado )
                $uf = strtoupper($estado->uf);
        }

        return view('venda.checkout',[
            'cliente' => (object) [
                'id' => 0,
                'nome' => $venda->nome,
                'cpf' => $venda->cpf,
                'email' => $venda->email,
                'telefone' => $venda->telefone,
                'cep' => $venda->cep,
                'estado' => $uf,
                'cidade' => $cidade_nome,
            ],
            'valor' => $venda->etapa->valor,
            'pedido' => $venda->key,
            'erros' => $erros,
        ]);
    }

    public function checkoutpagar( Request $request ){

        $erros = [];

        $requestCaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=". env('RECAPTCHA_SECRET_KEY') ."&response=". $request->g_recaptcha_response ."&remoteip=". $_SERVER['REMOTE_ADDR']);
        $responseCaptcha = json_decode($requestCaptcha);
        if( !$request->g_recaptcha_response or !$responseCaptcha or $responseCaptcha->success === false ){
            $erros[] = 'Captcha falhou! Tente novamente.';

            $request->merge([ 'erros' => $erros ]);
            return Self::checkout( $request, $request->pedido );
        }

        $venda = Venda::where('key', $request->pedido)->first();
        $matrizes = $venda->matrizes();
        
        $data = [
            "reference_id" => $venda->id,
            "description" => "Compra de Título: ".$matrizes[0]['matriz']['bilhete'],
            "amount" => [
                "value" => Helper::onlyNumbers($venda->etapa->valor),
                "currency" => "BRL"
            ],
            "payment_method" => [
                "type" => "CREDIT_CARD",
                "installments" => 1,
                "capture" => true,
                "soft_descriptor" => env('APP_NAME'),    
                "card" => [
                    "number" => Helper::onlyNumbers($request->cartao),
                    "exp_month" => $request->mes,
                    "exp_year" => $request->ano,
                    "security_code" => $request->cvv,
                    "holder" => [
                        "name" => $request->nome
                    ]
                ]
            ],
        ];
        if( ! env('PAGSEGURO_IS_SANDBOX') )
            $data['notification_urls'] = [ url('/callback') ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_URL, env('PAGSEGURO_URL').'/charges');
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: ".env('PAGSEGURO_TOKEN'),
            "Content-Type: application/json",
            "x-idempotency-key;",
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = json_decode(curl_exec($ch));
        $status_code = curl_getinfo($ch)['http_code'];
        curl_close($ch);

        if( isset( $response->status ) and in_array($response->status, [ 'AUTHORIZED', 'PAID' ]) ){

            if( !$venda->nome )
                $venda->nome = $request->nome;
            if( !$venda->cpf )
                $venda->cpf = Helper::onlyNumbers($request->cpf);
            if( !$venda->email )
                $venda->email = $request->email;
            if( !$venda->telefone )
                $venda->telefone = Helper::onlyNumbers($request->telefone);

            $venda->confirmada = 1;
            $venda->save();

            $venda->pagamento()->create([
                'venda_id' => $venda->id,
                'transaction_code' => $response->id,
                'tipo' => 'CREDITO',
                'status' => $response->status,
                'valor_bruto' => $venda->etapa->valor,
            ]);

            return redirect('comprovante/'.$venda->key);

        } else {
            $erros[] = 'Venda não Autorizada. Verifique seu cartão de crédito!';

            $request->merge([ 'erros' => $erros ]);
            return Self::checkout( $request, $request->pedido );
        }

        if( isset($response->error_messages) ){
            foreach( $response->error_messages as $error ){
                $str = $error->code .': '. $error->description;
                if( isset( $error->parameter_name ) )
                    $str .= ' ( '. $error->parameter_name .' )';
                $erros[] = $str;
            }

            $request->merge([ 'erros' => $erros ]);
            return Self::checkout( $request, $request->pedido );
        }
    }

    public function cancel( Request $request, $id ){

        $venda = Venda::findOrFail($id);

        // // CONSULTAR STATUS DA VENDA
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, env('PAGSEGURO_URL').'/charges/'. $venda->pagamento()->first()->transaction_code );
        // curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //     "Authorization: ".env('PAGSEGURO_TOKEN'),
        //     "Content-Type: application/json",
        //     "x-idempotency-key;",
        // ));
        // $response = json_decode(curl_exec($ch));
        // $status_code = curl_getinfo($ch)['http_code'];
        // curl_close($ch);
        // dd($response);
        // $response->reference_id
        // $response->status;
        // $response->amount->value;
        // $response->amount->summary->refunded;

        if( $venda->pagamento()->first()->tipo != 'CREDITO' )
            return response()->json([ 'error' => 'Estrono permitido apenas para CREDITO.' ], 400);

        $difference = ( new Carbon( $venda->created_at ) )->diff( Carbon::now() )->days;
        if( $difference >= 90 )
            return response()->json([ 'error' => 'Estrono não permitido após 90 dias.' ], 400);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_URL, env('PAGSEGURO_URL').'/charges/'. $venda->pagamento()->first()->transaction_code .'/cancel');
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'amount' => [ 'value' => Helper::onlyNumbers( $venda->etapa->valor ) ],
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: ".env('PAGSEGURO_TOKEN'),
            "Content-Type: application/json",
            "x-idempotency-key;",
        ));
        $response = json_decode(curl_exec($ch));
        $status_code = curl_getinfo($ch)['http_code'];
        curl_close($ch);

        if( isset($response->error_messages) ){
            foreach( $response->error_messages as $error ){

                // if( $error->code == 40007 ) break; // reembolso já realizado

                $str = $error->code .': '. $error->description;
                if( isset( $error->parameter_name ) )
                    $str .= ' ( '. $error->parameter_name .' )';
                $erros[] = $str;
            }
            return response()->json([ 'error' => $erros ], 400);
        }

        $venda->pagamento()->update([ 'status' => 'CANCELED' ]);
        $venda->confirmada = false;
        $venda->deleted_at = Carbon::now();
        $venda->save();

        return response()->json([
            'message' => 'Venda cancelada',
            'venda' => $venda,
            'redirectURL' => url('/vendas'),
        ], 200 );

    }

    public function getpixpayload( Request $request, $key ){
        
        $venda = Venda::where( 'key', $key )->first();
        if( !$venda )
            return response()->json([ 'erro' => 'Chave não localizada.' ], 404 );

        $bilhete = $venda->matrizes()[0]['matriz']['bilhete'];

        $pix = (new Pix)->setPixKey( env('PIX_KEY') )
              ->setDescription( env('APP_NAME')." TITULO ".$bilhete )
              ->setMerchantName( env('PIX_MERCHANT_NAME') )
              ->setMerchantCity( env('PIX_MERCHANT_CITY') )
              ->setAmount( $venda->etapa->valor )
              ->setUniquePayment( true )
              ->setTxid( $bilhete )
              ->getPayload();

        return response( $pix );

    }
    public function pix( Request $request, $key ){

        $venda = Venda::where( 'key', $key )->first();
        if( !$venda ){
            return response()->json([ 'erro' => 'Chave não localizada.' ], 404 );
        }

        if( isset($venda->pagamento) )
            return redirect('comprovante/'.$venda->key);

        $venda->pagamento()->create([
            'venda_id' => $venda->id,
            'tipo' => 'PIX',
            'status' => 'WAITING',
            'valor_bruto' => $venda->etapa->valor,
        ]);

        $pix = Self::getpixpayload($request, $key);
        $qrcode = (new Output\Png)->output( new QrCode($pix),250);

        return view('venda.pix',[ 
            'qrcode' => $qrcode,
            'pix' => $pix,
        ]);
    }

}
