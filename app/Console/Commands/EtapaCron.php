<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Mail;
use App\Etapa;

class EtapaCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ativaetapa:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ativa etapa quando expirar o tempo de validade';

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
        // ativa a próxima etapa disponível 
        foreach( ['semanal', 'mensal'] as $frequencia ){
            $etapa = Etapa::ativa($frequencia);
            if( $etapa ){
                if( strtotime( $etapa->data .' 23:59:59') < strtotime( date('Y-m-d H:i:s') ) ){
                    DB::table('etapas')->where('frequencia', $etapa->frequencia)->update([ 'ativa' => 0 ]);
                    DB::table('etapas')
                        ->whereRaw(' data > NOW()')
                        ->where('frequencia', $etapa->frequencia)
                        ->orderBy('data')
                        ->limit(1)
                        ->update([ 'ativa' => 1 ]);
                }
            }
        }
    }
}
