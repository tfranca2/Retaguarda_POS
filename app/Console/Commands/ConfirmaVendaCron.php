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

        $vendasNaoConfirmadas = Venda::where('confirmada',0)->whereRaw(' NOW() > date_add( created_at, interval 30 minute ) ')->get();
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

    }
}
