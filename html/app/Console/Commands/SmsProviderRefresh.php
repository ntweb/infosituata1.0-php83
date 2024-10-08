<?php

namespace App\Console\Commands;

use App\Models\Azienda;
use Illuminate\Console\Command;

class SmsProviderRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:provider-refresh';

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
    protected $description = 'Refresh dei credits';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $aziende = Azienda::where('module_sms', '1')
            ->where('module_sms_provider_refresh', '1')
            ->get();

        foreach ($aziende as $a) {
            switch ($a->module_sms_provider) {
                case 'trendoo';
                case 'esendex';
                    try {
                        $credits = refreshSmsProviderTrendoo($a);
                        $a->module_sms_provider_credits = $credits;
                        $a->module_sms_provider_refresh = '0';
                        $a->save();
                    }
                    catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                    break;
            }

        }

    }
}
