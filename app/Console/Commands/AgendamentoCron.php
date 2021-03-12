<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Mail;
use App\Contratacao;
use App\Mail\SendMailUser;

class AgendamentoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agendamento:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alerta o admin dos agendamentos sem prestador de serviço';

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

        $contratacoes = Contratacao::whereNull('prestador_id')
                        ->whereRaw('NOW() >= DATE_SUB( created_at, INTERVAL 1 MINUTE )')
                        ->orderBy('id')
                        ->with('servico')
                        ->get();
        
        foreach( $contratacoes as $contratacao ) {

            // DB::statement("UPDATE contratacao SET prestador_id = 1 WHERE id = ". $contratacao->id ." AND ( prestador_id IS NULL OR prestador_id = 1 OR prestador_id = 0 OR prestador_id = '' )");
            
            Mail::to( 'tfranca2@gmail.com' )->send( new SendMailUser( 
                'Serviço agendado sem prestador', 
                'email.servicoSemPrestador', 
                [ 
                    'servico' => $contratacao->servico->nome, 
                    'inicio' => $contratacao->inicio, 
                    'empresa' => config('app.name'), 
                ] 
            ) );

        }

    }
}
