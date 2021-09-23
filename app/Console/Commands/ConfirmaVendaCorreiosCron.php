<?php

namespace App\Console\Commands;

use DB;
use Mail;
use App\Venda;
use App\VendaMatriz;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConfirmaVendaCorreiosCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'confirmavendacorreios:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove vendas não confirmadas após 30 minutos';

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
    public function handle()
    {

        $vendasNaoConfirmadasCorreios = Venda::where('confirmada',1)->whereNotNull('protocolo')->whereRaw(' created_at >= DATE_ADD( CURDATE(), INTERVAL - 1 DAY ) ')->get();
        foreach( $vendasNaoConfirmadasCorreios as $venda ){

            DB::beginTransaction();
            try {

                // consultar por protocolo
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, env('URL_CORREIOS').'/ster/api/v1/atendimentos/numerocontrato/'. env('CONTRATO_CORREIOS') .'/protocolo/'. $venda->protocolo .'/detalhes');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_HTTPHEADER,[ 
                    "accept: application/json", 
                    "Content-Type: application/json", 
                    "Authorization: ". env('TOKEN_CORREIOS'),
                ]);
                $response = curl_exec($ch);
                $status_code = curl_getinfo($ch)['http_code'];
                curl_close($ch);

                $response = json_decode($response);
                if ($status_code >= 300 || $status_code < 200 || !$response ) {
                    throw new \Exception('### API CORREIOS ### status '. $status_code .', protocolo '. $venda->protocolo);
                }

                if( $response and is_object($response) and isset($response->statusAtendimento) ){
                        // F - Finalizada
                    if( $response->statusAtendimento == 'F'){
                        $venda->confirmada = true;
                        $venda->save();

                        // P - Pendente
                        // C - Cancelada
                    } elseif( in_array($response->statusAtendimento, [ 'P', 'C' ]) ){
                        VendaMatriz::where('venda_id',$venda->id)->delete();
                        Venda::where('id',$venda->id)->delete();
                    }
                }

                DB::commit();
            } catch( Exception $e ){
                DB::rollBack();
            }

        }

    }
}
