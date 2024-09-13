<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApiTokenDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-token:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancella i token non utilizzati';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('personal_access_tokens')
            ->where('last_used_at', '<', now()->subMonths(2))
            ->delete();

        DB::table('personal_access_tokens')
            ->whereNull('last_used_at')
            ->where('created_at', '<', now()->subMonths(2))
            ->delete();

        return true;
    }
}
