<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Mail;
use App\Venda;
use App\VendaMatriz;

class ConfirmaVendaCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'confirmavenda:cron';

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

        $vendasNaoConfirmadas = Venda::where('confirmada',0)->whereNull('protocolo')->whereRaw(' NOW() > date_add( created_at, interval 30 minute ) ')->get();
        foreach( $vendasNaoConfirmadas as $venda ){
            DB::beginTransaction();
            try {
                VendaMatriz::where('venda_id',$venda->id)->delete();
                Venda::where('id',$venda->id)->delete();
                DB::commit();
            } catch( Exception $e ){
                DB::rollBack();
            }
        }

        $vendasNaoConfirmadasCorreios = Venda::where('confirmada',0)->whereNotNull('protocolo')->whereRaw(' NOW() > date_add( created_at, interval 1 day ) ')->get();
        foreach( $vendasNaoConfirmadasCorreios as $venda ){

            DB::beginTransaction();
            try {

                // consultar protocolo
                $data = [
                    'numeroProtocolo' => $venda->protocolo,
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_URL, env('URL_CORREIOS').'/ster/api/confirmarAtendimento');
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER,[ 
                    "accept: application/json", 
                    "Content-Type: application/json", 
                    "Authorization: ". env('TOKEN_CORREIOS'),
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $response = curl_exec($ch);
                $status_code = curl_getinfo($ch)['http_code'];
                curl_close($ch);

                $response = json_decode($response);
                if ($status_code >= 300 || $status_code < 200 || !$response ) {
                    if( $response and is_object($response) and isset($response->codigoConfirmacao) and $response->codigoConfirmacao == '99' ){
                        VendaMatriz::where('venda_id',$venda->id)->delete();
                        Venda::where('id',$venda->id)->delete();
                    }
                } else {
                    $venda->confirmada = true;
                    $venda->save();
                }

                DB::commit();
            } catch( Exception $e ){
                DB::rollBack();
            }

        }

    }
}
