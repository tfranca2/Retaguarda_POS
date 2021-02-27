<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prestador;
use App\PrestadorServico;
use App\PrestadorBanco;
use App\PrestadorReferencia;
use App\PrestadorDocumento;
use App\Servico;
use App\User;
use Session;
use Validator;
use App\Helpers\Helper;
use DB;
use App\Contratacao;
use App\Cliente;

class PrestadorController extends Controller
{
 
    public function getAll( Request $request ){

        $prestadores = new Prestador();
        $prestadores = $prestadores->newQuery();

        if( $request->has('nome') ){
            if( $request->input('nome') ){

                $prestadores->whereHas('usuario', function ($query) use ($request) {
                    $query->where('name', $request->input('nome'));
                });

            }
        }

        if( $request->has('cpf') ){
            if( $request->input('cpf') ){

                $prestadores->whereHas('usuario', function ($query) use ($request) {
                    $query->where('cpf',  Helper::onlyNumbers( $request->input('cpf') ) );
                });

            }
        }

        if( $request->has('telefone') ){
            if( $request->input('telefone') ){

                $prestadores->where('celular', Helper::onlyNumbers( $request->input('telefone') ) );

            }
        }

        if( $request->has('email') ){
            if( $request->input('email') ){

                $prestadores->whereHas('usuario', function ($query) use ($request) {
                    $query->where('email', $request->input('email') );
                });

            }
        }

        if( $request->has('servico_id') ){
            if( $request->input('servico_id') ){

                $prestadores->whereHas('servicos', function ($query) use ($request) {
                    $query->where('servico_id', $request->input('servico_id') );
                });

            }
        }

        if( $request->has('ativo') ){

            if( $request->input('ativo') != null ) {
                if( $request->input('ativo') == 1 )
                    $prestadores->whereNull('deleted_at');
                elseif( $request->input('ativo') == 0 )
                    $prestadores->whereNotNull('deleted_at');
            }
        }

        $prestadores = $prestadores->select(['prestador.*'])
                                    ->join('users', function($q){
                                        $q->on('prestador.usuario_id', '=', 'users.id')
                                          ->whereNull('users.deleted_at');
                                    })
                                    ->orderBy('users.name')
                                    ->with('usuario')
                                    ->with('servicos')
                                    ->paginate(10);
        
        return response()->json( $prestadores, 200 );

    }

    public function index( Request $request ){

        $prestadores = new Prestador();
        $prestadores = $prestadores->newQuery();

        if( $request->has('nome') ){
            if( $request->input('nome') ){

                $prestadores->whereHas('usuario', function ($query) use ($request) {
                    $query->where('name', $request->input('nome'));
                });

            }
        }

        if( $request->has('cpf') ){
            if( $request->input('cpf') ){

                $prestadores->whereHas('usuario', function ($query) use ($request) {
                    $query->where('cpf',  Helper::onlyNumbers( $request->input('cpf') ) );
                });

            }
        }

        if( $request->has('telefone') ){
            if( $request->input('telefone') ){

                $prestadores->where('celular', Helper::onlyNumbers( $request->input('telefone') ) );

            }
        }

        if( $request->has('email') ){
            if( $request->input('email') ){

                $prestadores->whereHas('usuario', function ($query) use ($request) {
                    $query->where('email', $request->input('email') );
                });

            }
        }

        if( $request->has('servico_id') ){
            if( $request->input('servico_id') ){

                $prestadores->whereHas('servicos', function ($query) use ($request) {
                    $query->where('servico_id', $request->input('servico_id') );
                });

            }
        }

        if( $request->has('ativo') ){

            if( $request->input('ativo') != null ) {
                if( $request->input('ativo') == 1 )
                    $prestadores->whereNull('deleted_at');
                elseif( $request->input('ativo') == 0 )
                    $prestadores->whereNotNull('deleted_at');
            }
        }

        $prestadores = $prestadores->select(['prestador.*'])
                                    ->join('users', function($q){
                                        $q->on('prestador.usuario_id', '=', 'users.id')
                                          ->whereNull('users.deleted_at');
                                    })
                                    ->orderBy('users.name')
                                    ->paginate(10);

        foreach( $prestadores as $prestador ){
            $prestador->rating = Contratacao::selectRaw('AVG( rating ) AS rating')->where('prestador_id',$prestador->id)->get()[0]->rating;
        }

        $servicos = Servico::All();

    	return view('prestador.index',[ 'prestadores' => $prestadores, 'servicos' => $servicos ]);

    }

    public function create( Request $request ){

        $usados = array_values( Prestador::distinct()->pluck('usuario_id')->toArray() );
        $usuarios = User::whereNotIn( 'id', $usados )->get();

        $servicos = Servico::All();

        Prestador::select('usuario_id')->distinct();

    	return view('prestador.form',[ 'usuarios' => $usuarios, 'servicos' => $servicos ]);

    }
    
    public function store( Request $request ){

        $validator = Validator::make($request->all(), [
            'usuario_id'   => 'required|integer|unique:prestador,usuario_id',
            'rg'           => 'required|string|max:255',
            'celular'      => 'required|string|max:255',
            'servico_id'   => 'required',
            'latitude'     => 'required|string|max:255',
            'longitude'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        DB::beginTransaction();
        try {

            if( $request->has('ativo') )
                $prestador['deleted_at'] = null;
            else 
                $prestador['deleted_at'] = date('Y-m-d H:i:s');
     
            $prestador['usuario_id'] = $request->usuario_id;
            $prestador['rg'] = $request->rg;
            $prestador['cep'] = $request->cep;
            $prestador['endereco'] = $request->endereco;
            $prestador['numero'] = $request->numero;
            $prestador['bairro'] = $request->bairro;
            $prestador['cidade'] = $request->cidade;
            $prestador['estado'] = $request->estado;
            $prestador['latitude'] = $request->latitude;
            $prestador['longitude'] = $request->longitude;
            $prestador['celular'] = $request->celular;
            $prestador['fixo'] = $request->fixo;
            $prestador['observacoes'] = $request->observacoes;
            $prestador['mae'] = $request->mae;
            $prestador['pai'] = $request->pai;

            if( $request->has('checkantecedentes') )
                $prestador['checkantecedentes'] = 1;
            else 
                $prestador['checkantecedentes'] = 0;

            $prestador = Prestador::create($prestador);
            $prestador_id = $prestador->id;

            // servicos
            if( $request->servico_id )
            foreach( $request->servico_id as $key => $value ){
                $servico['prestador_id'] = $prestador_id;
                $servico['servico_id'] = $value;
                $servico['experiencia'] = $request->tempoexp[ $key ] .' '. $request->unidadetempoexp[ $key ];

                PrestadorServico::create( $servico );
            }

            // bancos
            if( $request->banco )
            foreach( $request->banco as $key => $value ){
                if( $key ){
                    $banco['prestador_id'] = $prestador_id;
                    $banco['banco'] = $value;
                    $banco['agencia'] = $request->agencia[ $key ];
                    $banco['conta'] = $request->conta[ $key ];
                    $banco['operacao'] = $request->operacao[ $key ];

                    PrestadorBanco::create( $banco );
                }
            }

            // referencias
            if( $request->empresa )
            foreach( $request->empresa as $key => $value ){
                if( $key ){
                    $referencia['prestador_id'] = $prestador_id;
                    $referencia['empresa'] = $value;
                    $referencia['contato'] = $request->contato[ $key ];
                    $referencia['email'] = $request->email[ $key ];
                    $referencia['telefone'] = $request->telefone[ $key ];

                    PrestadorReferencia::create( $referencia );
                }
            }

            // documentos
            if( $request->documento )
            foreach( $request->documento as $key => $value ){
                $documento['prestador_id'] = $prestador_id;
                $documento['documento'] = $value;
                $documento['numeracao'] = $request->numeracao[$key];

                // UPLOAD IMAGEM
                if( $request->foto[$key] ){
                    $imageName = \Str::random(20). time() .'.'. request()->foto[$key]->getClientOriginalExtension();
                    request()->foto[$key]->move( public_path('images'), $imageName );
                    $documento['foto'] = $imageName;
                }

                PrestadorDocumento::create( $documento );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'error' => $e->getMessage() ], 503);
        }

    	return response()->json([ 'message' => 'Criado com sucesso', 'redirectURL' => url('/prestadores'), 'prestador' => $prestador ], 201 );

    }
    
    public function show( Request $request, $id ){
        
        try {

        	return response()->json( Prestador::findOrFail($id)->with('usuario')->with('servicos')->first() );

        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }

    }
    
    public function edit( Request $request, $id ){

        $prestador = Prestador::findOrFail($id);
        $servicos = Servico::All();

        $usados = array_values( Prestador::distinct()->pluck('usuario_id')->toArray() );
        $usuarios = User::whereNotIn( 'id', $usados )->get();
        $usuarios->push( $prestador->usuario()->first() );

    	return view('prestador.form',[ 'prestador' => $prestador, 'usuarios' => $usuarios, 'servicos' => $servicos ]);

    }
    
    public function update( Request $request, $id ){

        $validator = Validator::make($request->all(), [
            'usuario_id'   => 'required|integer|unique:prestador,usuario_id,'. $id,
            'rg'           => 'required|string|max:255',
            'celular'      => 'required|string|max:255',
            'servico_id'   => 'required',
            'latitude'     => 'required|string|max:255',
            'longitude'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        DB::beginTransaction();
        try {

            $prestador = Prestador::find($id);
            
            if( $request->has('ativo') )
            	$prestador->deleted_at = null;
            else 
            	$prestador->deleted_at = date('Y-m-d H:i:s');

            $prestador->usuario_id = $request->usuario_id;

            $prestador->rg = $request->rg;
            $prestador->cep = $request->cep;
            $prestador->endereco = $request->endereco;
            $prestador->numero = $request->numero;
            $prestador->bairro = $request->bairro;
            $prestador->cidade = $request->cidade;
            $prestador->estado = $request->estado;
            $prestador->latitude = $request->latitude;
            $prestador->longitude = $request->longitude;
            $prestador->celular = $request->celular;
            $prestador->fixo = $request->fixo;
            $prestador->observacoes = $request->observacoes;
            $prestador->mae = $request->mae;
            $prestador->pai = $request->pai;

            if( $request->has('checkantecedentes') )
                $prestador->checkantecedentes = 1;
            else 
                $prestador->checkantecedentes = 0;

            $prestador->save();
            $prestador_id = $id;

            // servicos
            $servicos = array();
            if( $request->servico_id )
            foreach( $request->servico_id as $key => $value ){
                $servico['prestador_id'] = $prestador_id;
                $servico['servico_id'] = $value;
                $servico['experiencia'] = $request->tempoexp[ $key ] .' '. $request->unidadetempoexp[ $key ];

                $servicos[] = $servico;
            }

            if( $servicos ){
                $prestador->servicos()->delete();
                foreach( $servicos as $servico ){
                    PrestadorServico::create( $servico );
                }
            }

            // bancos
            $bancos = array();
            if( $request->banco )
            foreach( $request->banco as $key => $value ){
                if( $key ){
                    $banco['prestador_id'] = $prestador_id;
                    $banco['banco'] = $value;
                    $banco['agencia'] = $request->agencia[ $key ];
                    $banco['conta'] = $request->conta[ $key ];
                    $banco['operacao'] = $request->operacao[ $key ];

                    $bancos[] = $banco;
                }
            }
            if( $bancos ){
                $prestador->bancos()->delete();
                foreach( $bancos as $banco ){
                    PrestadorBanco::create( $banco );
                }
            }

            // referencias
            $referencias = array();
            if( $request->empresa )
            foreach( $request->empresa as $key => $value ){
                if( $key ){
                    $refetencia['prestador_id'] = $prestador_id;
                    $refetencia['empresa'] = $value;
                    $refetencia['contato'] = $request->contato[ $key ];
                    $refetencia['email'] = $request->email[ $key ];
                    $refetencia['telefone'] = $request->telefone[ $key ];

                    $referencias[] = $refetencia;
                }
            }
            if( $referencias ){
                $prestador->referencias()->delete();
                foreach( $referencias as $referencia ){
                    PrestadorReferencia::create( $referencia );
                }
            }

            // documentos
            $count = 0;
            if( $request->documento )
            foreach( $request->documento as $key => $value ){
                
                if( isset( $request->documento_id[$key] ) and $request->documento_id[$key] ){

                    $documento = PrestadorDocumento::find( $request->documento_id[$key] );
                    $documento->documento = $value;
                    $documento->numeracao = $request->numeracao[$key];
                    $documento->save();

                } else {

                    $documento = array();
                    $documento['prestador_id'] = $prestador_id;
                    $documento['documento'] = $value;
                    $documento['numeracao'] = $request->numeracao[$key];

                    // UPLOAD IMAGEM
                    if( $request->foto[$count] ){
                        $imageName = \Str::random(20). time() .'.'. request()->foto[$count]->getClientOriginalExtension();
                        request()->foto[$count]->move( public_path('images'), $imageName );
                        $documento['foto'] = $imageName;
                    }

                    PrestadorDocumento::create( $documento );

                    $count++;
                }

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'error' => $e->getMessage() ], 503);
        }

    	return response()->json([ 'message' => 'Atualizado com sucesso', 'redirectURL' => url('/prestadores'), 'prestador' => $prestador ], 200 );

    }
    
    public function destroy( Request $request, $id ){

    	$prestador = Prestador::findOrFail($id);

        $prestador->servicos()->delete();
        $prestador->bancos()->delete();
        $prestador->referencias()->delete();
        $prestador->documentos()->delete();
        
        $prestador->delete();

    	return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }

    public static function nasProximidades( Request $request ){

        $validator = Validator::make($request->all(), [
            'latitude'     => 'required|string|max:255',
            'longitude'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ], 400 );
        }

        $prestadores = Prestador::get();
        return response()->json( $prestadores, 200 );

        $distancia_de_busca = 10; // Km
        if( $request->has('distancia') and $request->distancia > 0 )
            $distancia_de_busca = $request->distancia;

        function calcDistancia($lat1, $long1, $lat2, $long2)
        {
            $d2r = 0.017453292519943295769236;

            $dlong = ($long2-$long1)*$d2r;
            $dlat = ($lat2-$lat1)*$d2r;

            $temp_sin = sin($dlat/2.0);
            $temp_cos = cos($lat1*$d2r);
            $temp_sin2 = sin($dlong/2.0);

            $a = ($temp_sin*$temp_sin)+($temp_cos*$temp_cos)*($temp_sin2*$temp_sin2);
            $c = 2.0 * atan2(sqrt($a),sqrt(1.0-$a));

            return 6368.1 * $c;
        }

        // 1º latitude, longitude == 111,12 km

        $prestadores = Prestador::whereBetween('latitude', [ $request->latitude-1, $request->latitude+1 ])
                                ->whereBetween('longitude', [ $request->longitude-1, $request->longitude+1 ])
                                ->get();

        $pontos = array();
        foreach( $prestadores as $prestador ){

            $distancia = calcDistancia( $request->latitude, $request->longitude, str_replace(',', '.', $prestador->latitude ), str_replace(',', '.', $prestador->longitude ) );

            if( $distancia <= $distancia_de_busca ){
                $pontos[] = $prestador;
            }
        }

        if( count($pontos)>0 )
            return response()->json( $pontos, 200 );

        return response()->json([ 'error' => 'Nenhum estabelecimento nas proximidades.' ], 404 );
    }


    public function relatorio( Request $request, $id ){

        $prestador = Prestador::where('id',$id)->with('usuario')->first();

        $agendamentos = Contratacao::where('prestador_id',$id)
                        ->with('cliente')
                        ->with('prestador')
                        ->with('pagamento');

        if( $request->has('data') and $request->data[0] ){
            $agendamentos = $agendamentos->whereBetween('inicio',$request->data)
                                        ->whereBetween('fim',$request->data);
        }

        $clientes = Cliente::whereIn( 'id', 
            $agendamentos->distinct()->pluck('cliente_id')->toArray() 
        )->get();

        $servicos = Servico::whereIn( 'id', 
            $agendamentos->distinct()->pluck('servico_id')->toArray() 
        )->get();

        if( $request->has('cliente') and $request->cliente ){
            $agendamentos = $agendamentos->where( 'cliente_id', $request->cliente );
        }

        if( $request->has('servico') and $request->servico ){
            $agendamentos = $agendamentos->where( 'servico_id', $request->servico );
        }

        if( $request->has('cancelado') and $request->cancelado != null ){
            
            $status = [3,4];
            if( $request->cancelado == 1 )
               $status = [6,7];

            $agendamentos = $agendamentos->whereHas( 'pagamento', function ( $query ) use ( $status ){
                return $query->whereIn( 'status', $status );
            });
        }

        $agendamentos = $agendamentos->orderBy('inicio','DESC')->paginate(10);

        return view('prestador.relatorio',[ 
            'prestador' => $prestador, 
            'agendamentos' => $agendamentos, 
            'clientes' => $clientes, 
            'servicos' => $servicos, 
        ]);

    }

    public function csv( Request $request, $id ){

        $prestador = Prestador::where('id',$id)->with('usuario')->first();

        $agendamentos = Contratacao::where('prestador_id',$id)
                        ->with('cliente')
                        ->with('prestador')
                        ->with('pagamento');

        if( $request->has('data') and $request->data[0] ){
            $agendamentos = $agendamentos->whereBetween( 'inicio', $request->data )
                                        ->whereBetween( 'fim', $request->data );
        }

        if( $request->has('cliente') and $request->cliente ){
            $agendamentos = $agendamentos->where( 'cliente_id', $request->cliente );
        }

        if( $request->has('servico') and $request->servico ){
            $agendamentos = $agendamentos->where( 'servico_id', $request->servico );
        }

        if( $request->has('cancelado') and $request->cancelado != null ){
            
            $status = [3,4];
            if( $request->cancelado == 1 )
               $status = [6,7];

            $agendamentos = $agendamentos->whereHas( 'pagamento', function ( $query ) use ( $status ) {
                return $query->whereIn( 'status', $status );
            });
        }

        $agendamentos = $agendamentos->orderBy('inicio','DESC')->get();

        $nome = '';
        if( $prestador->usuario )
        $nome = utf8_decode($prestador->usuario->name);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; charset=UTF-8; filename=". date('YmdHis') . $nome .".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        if( $prestador->usuario )
            $nome .= ' - '.Helper::formatCpfCnpj($prestador->usuario->cpf);

        $callback = function() use ( $nome, $agendamentos )
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, [ $nome ], ';', '"', "\n");
            fputcsv($file, [ utf8_decode('Serviço'), 'Cliente', 'Data', 'Valor', 'Gorjeta', utf8_decode('Avaliação'), 'Cancelamento' ], ';', '"', "\n");

            foreach( $agendamentos as $agendamento ){
                $servico = '';
                if($agendamento->servico)
                    $servico = $agendamento->servico->nome;

                $cliente = '';
                if($agendamento->cliente)
                    $cliente = utf8_decode($agendamento->cliente->nome);

                $cancelado = '';
                if( $agendamento->pagamento and in_array( $agendamento->pagamento->status, [ 6, 7]) )
                    $cancelado = $agendamento->pagamento->updated_at;

                $rating = $agendamento->rating;
                if( $agendamento->justificativa )
                    $rating .= ' - '. utf8_decode($agendamento->justificativa);

                fputcsv( $file, [ 
                    $servico,
                    $cliente,
                    $agendamento->inicio,
                    Helper::formatDecimalToView($agendamento->valor),
                    Helper::formatDecimalToView($agendamento->gorjeta),
                    $rating,
                    $cancelado,
                 ], ';', '"', "\n" );
            }
            fclose($file);
        };

        return \Response::stream( $callback, 200, $headers );

    }

}
