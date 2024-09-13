<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NoSSH extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'no-ssh:run';

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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'In caso di mancato accesso a SSH inserire qui i comandi la lanciare';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Artisan::call('migrate');
    }
}
