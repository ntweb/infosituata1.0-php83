<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generic test';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subject = 'Nuovo controllo attrezzatura inserito';
        $message = route('controllo.edit', '123456');
        sendEmailGenerica('mimmomecca@gmail.com' , [], $subject, $message);
    }
}
