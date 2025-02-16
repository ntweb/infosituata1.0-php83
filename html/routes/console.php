<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

//Artisan::command('inspire', function () {
//    $this->comment(Inspiring::quote());
//})->purpose('Display an inspiring quote')->hourly();

Schedule::command('scadenze:notify')->everyMinute()->withoutOverlapping(5);
Schedule::command('delete:items')->everyMinute();

Schedule::command('sms:send')->everyMinute()->withoutOverlapping(5);
Schedule::command('sms:provider-refresh')->everyMinute()->withoutOverlapping(5);

Schedule::command('controllo-mezzi:notify')->everyMinute()->withoutOverlapping(5);
Schedule::command('controllo-attrezzatura:notify')->everyMinute()->withoutOverlapping(5);

Schedule::command('whatsapp:send-welcome')->everyMinute()->withoutOverlapping(5);
Schedule::command('whatsapp:send')->everyMinute()->withoutOverlapping(5);

Schedule::command('topic:notify')->everyFiveMinutes()->withoutOverlapping(5);

Schedule::command('make:fattura')->daily();

Schedule::command('delete:s3')->everyFiveMinutes()->withoutOverlapping(5);

Schedule::command('clienti:import')->everyMinute()->withoutOverlapping(5);

Schedule::command('zoho:refresh-token')->everyTenMinutes()->withoutOverlapping(5);
Schedule::command('tickets:send')->everyFiveMinutes()->withoutOverlapping(5);
Schedule::command('tickets:tickets:delete-attachments')->daily();

Schedule::command('s3-backup:run')->daily()->at('14:00');
Schedule::command('s3-backup:run')->daily()->at('19:00');

Schedule::command('queue:restart')->everyFiveMinutes();
Schedule::command('queue:flush')->everyFiveMinutes();
Schedule::command('queue:work --tries=3')->everyFiveMinutes();
