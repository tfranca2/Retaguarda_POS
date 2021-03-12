<?php

namespace App\Console\Commands;

header("access-control-allow-origin: ". env('PAGSEGURO_URL', '') );

use DB;
use App\Payment;
use App\Contratacao;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PagseguroCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagseguro:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica o status de cada contratacao junto ao Pagseguro';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){

        $statusOrder = array( 1 => 'Agendada', 2 => 'Processando', 3 => 'Não Processada', 4 => 'Suspensa', 5 => 'Paga', 6 => 'Não Paga' );
        $statusTransaction = array( 1 => 'Aguardando pagamento', 2 => 'Em análise', 3 => 'Paga', 4 => 'Disponível', 5 => 'Em disputa', 6 => 'Devolvida', 7 => 'Cancelada' );

        $log = array();

        $contratacoes = DB::select("SELECT * FROM contratacao WHERE transaction_code IS NOT NULL AND id NOT IN ( SELECT DISTINCT contratacao.id FROM contratacao JOIN payments ON payments.transaction_code = contratacao.transaction_code )");
        foreach( $contratacoes as $contratacao ){

            $url = env('PAGSEGURO_URL', '') ."v2/transactions/". $contratacao->transaction_code ."?email=". env('PAGSEGURO_EMAIL','') ."&token=". env('PAGSEGURO_TOKEN','');
            $response = file_get_contents($url);
            if( $response ) {

                $resp = new \SimpleXMLElement( $response );

                $payment = array();
                $payment['status'] = (Integer) $resp->status;
                $payment['message'] = $statusTransaction[$payment['status']];
                $payment['transaction_code'] = (String) $resp->code;
                $payment['date'] = date( 'Y-m-d H:i:s', strtotime( (String) $resp->lastEventDate ));
                $payment['valorBruto'] = (String) $resp->grossAmount;
                $payment['taxa'] = (String) $resp->feeAmount;
                $payment['valorLiquido'] = (String) $resp->netAmount;
                $payment['valorExtra'] = (String) $resp->extraAmount;
                $payment['contratacao_id'] = (String) $resp->reference;

                $contratacao = Contratacao::find( $payment['contratacao_id'] );
                $cliente = $contratacao->cliente()->first();

                $payment['cliente_id'] = $cliente->id;

                $request = new \Illuminate\Http\Request();
                $request->latitude = $cliente->latitude;
                $request->longitude = $cliente->longitude;

                $pagamento = Payment::where('contratacao_id',$payment['contratacao_id'])->first();
                if( $pagamento ){
                    Payment::where('contratacao_id',$payment['contratacao_id'])->update($payment);
                } else
                    Payment::create($payment);

                if( in_array( $payment['status'], [ 3, 4 ] ) ){
                    app('App\Http\Controllers\ServicoController')->notificarPrestadores( $request, (object) array( 'id' => $payment['contratacao_id'] ))->getData();
                }

            }

        }

    }
}
