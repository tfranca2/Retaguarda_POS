<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\EmailCron::class,
        Commands\AgendamentoCron::class,
        Commands\PagseguroCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // VERIFICA A FILA E DISPARA OS EMAILS
        // $schedule->command('email:cron')->appendOutputTo( storage_path('logs/email-'.date('Y-m-d').'.log') );

        // ALERTA O ADMIN DOS AGENDAMENTOS SEM PRESTADOR
        $schedule->command('agendamento:cron');


        // VERIFICA DIARIAMENTE OS PAGAMENTOS NA API DO PAGSEGURO
        $schedule->command('pagseguro:cron')
                ->dailyAt('00:00')
                ->appendOutputTo( storage_path('logs/pagseguro.log') );
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
