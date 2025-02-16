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
        Commands\SendScadenzeNotification::class,
        Commands\DeleteItems::class,
        Commands\NoSSH::class,
        Commands\MakeFattura::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('scadenze:notify')->everyMinute()->withoutOverlapping(5);
        $schedule->command('delete:items')->everyMinute();

        $schedule->command('sms:send')->everyMinute()->withoutOverlapping(5);
        $schedule->command('sms:provider-refresh')->everyMinute()->withoutOverlapping(5);

        $schedule->command('controllo-mezzi:notify')->everyMinute()->withoutOverlapping(5);
        $schedule->command('controllo-attrezzatura:notify')->everyMinute()->withoutOverlapping(5);

        $schedule->command('whatsapp:send-welcome')->everyMinute()->withoutOverlapping(5);
        $schedule->command('whatsapp:send')->everyMinute()->withoutOverlapping(5);

        $schedule->command('topic:notify')->everyFiveMinutes()->withoutOverlapping(5);

        $schedule->command('make:fattura')->daily();

        $schedule->command('delete:s3')->everyFiveMinutes()->withoutOverlapping(5);

        $schedule->command('clienti:import')->everyMinute()->withoutOverlapping(5);

        // $schedule->command('zoho:refresh-token')->everyTenMinutes()->withoutOverlapping(5);
        $schedule->command('tickets:send')->everyFiveMinutes()->withoutOverlapping(5);
        $schedule->command('tickets:tickets:delete-attachments')->daily();

        $schedule->command('s3-backup:run')->daily()->at('14:00');
        $schedule->command('s3-backup:run')->daily()->at('19:00');

        $schedule->command('queue:restart')->everyFiveMinutes();
        $schedule->command('queue:flush')->everyFiveMinutes();
        $schedule->command('queue:work --tries=3')->everyFiveMinutes();

        //$schedule->command('no-ssh:run')->everyMinute();
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
