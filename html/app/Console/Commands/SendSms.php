<?php

namespace App\Console\Commands;

use App\Mail\CacciatoreAvviso;
use App\Mail\NotificaListe;
use App\Models\PrenotazioneDay;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PDF;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send';

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
    protected $description = 'Invia gli sms programmati';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sms = \App\Models\Sms::where('to_send', '1')
                        ->with(['azienda', 'utenti'])->get();

        foreach ($sms as $s) {
            $telephones = [];
            $this->info('-------------------');
            $this->info('SMS: '. $s->oggetto . ' - Azi: ' . $s->azienda->label);
            if (count($s->utenti)) {
                foreach ($s->utenti as $u) {
                    $this->info('sending to...: '. $u->extras1.' '.$u->extras2);

                    if(trim($u->extras4)) {
                        $telephones[] = trim($u->extras4);
                    }
                }
            }

            if (count($telephones)) {
                switch ($s->azienda->module_sms_provider) {
                    case 'trendoo';
                    case 'esendex';
                        try {
                            sendSmsTrendoo($s, $telephones, $s->azienda, $s->azienda->module_sms_provider);
                            $s->to_send = '0';
                            $s->azienda->module_sms_provider_refresh = '1';
                            $s->azienda->save();
                        }
                        catch (\Exception $e) {
                            $this->error($e->getMessage());
                            $s->exception = $e->getMessage();
                            $s->save();
                        }
                        break;
                }
            }

            $s->to_send = '0';
            $s->save();

        }

    }
}
