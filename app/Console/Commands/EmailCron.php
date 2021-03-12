<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Validation\ValidationException;
use Exception;
use Mail;
use App\Emails;
use App\Mail\SendMail;

class EmailCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispara os e-mails da fila do banco de dados';

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

        $log = array();

        $emails = Emails::whereNull('send')
                ->orderBy('id')
                ->get();
                
        foreach( $emails as $email ) {

            $erro = '';
            try {
                
                Mail::to( $email->sendTo )->send( new SendMail( $email->subject, $email->blade, ( (Array) json_decode( $email->data ) ) ) );


            } catch( Exception $e ) {
                $erro = $e->getMessage();
            }

            if( !$erro ) {
                $email->send = 1;
                $email->save();
            } else {

                // erro no envio 
                $log[] = array( 
                            'id' => $email->id, 
                            'to' => $email->sendTo, 
                            'subject' => $email->subject, 
                            'error' => $erro, 
                         );

                // email criado a mais de 5 min
                $to_time = strtotime( date('Y-m-d H:i:s') );
                $from_time = strtotime( $email->created_at );
                if( floor( abs( $to_time - $from_time ) / 60 ) > 5 ){

                    $dados = $email->toArray();
                    $dados['error'] = $erro;

                    // avisa ao admin
                    Mail::to( 'contato@adn23.com.br' )->send( new SendMail( config('app.name').' - Email pendente de envio', 'emails.pendingsend', $dados ) );

                }

                
            }

        }

        if( count( $log ) > 0 )
            echo "[". date('Y-m-d H:i:s') ."] ". json_encode( $log ) .";\n\n";

    }
}
