<?php

namespace App\Console\Commands;

use DB;
use Mail;
use App\Venda;
use App\VendaMatriz;
use App\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
    protected $description = 'Remove vendas não confirmadas após 2 horas';

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

        $vendasNaoConfirmadas = Venda::where('confirmada',0)->whereNull('protocolo')->whereRaw(' NOW() > date_add( created_at, interval 2 hour ) ')->get();
        foreach( $vendasNaoConfirmadas as $venda ){
            DB::beginTransaction();
            try {
                if( isset( $venda->pagamento ) )
                    Payment::where('venda_id',$venda->id)->delete();
                VendaMatriz::where('venda_id',$venda->id)->delete();
                Venda::where('id',$venda->id)->delete();
                DB::commit();
            } catch( Exception $e ){
                DB::rollBack();
            }
        }
        
    }
}
