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
        Commands\EtapaCron::class,
        Commands\ConfirmaVendaCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // ATIVA A PROXIMA ETAPA
        $schedule->command('ativaetapa:cron')->dailyAt('00:00');
                // ->appendOutputTo( storage_path('logs/ativaetapa-'.date('Y-m-d').'.log') );

        // REMOVE VENDAS NAO CONFIRMADAS
        $schedule->command('confirmavenda:cron');

        // VERIFICA A FILA E DISPARA OS EMAILS
        // $schedule->command('email:cron')->appendOutputTo( storage_path('logs/email-'.date('Y-m-d').'.log') );

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
