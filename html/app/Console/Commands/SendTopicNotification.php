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

class SendTopicNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topic:notify';

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
    protected $description = 'Invia notifica su topic non letti';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Log::info('aaaaaaaaa');
        $now = \Carbon\Carbon::now();
        // $topics = \App\Models\Pivot\MessaggioTopicNotify::where('created_at', '>=', $now->subMinutes(30))
        $topics = \App\Models\Pivot\MessaggioTopicNotify::where('created_at', '>=', $now->subMinutes(5))
            ->whereNull('sent_at')
            ->with(['topic', 'utente', 'utente.user'])
            ->get();

        $this->info($topics->count());
        // die();

        $alreadySend = [];
        foreach ($topics as $t) {
            if ($t->topic->module === 'topic') {
                if (!isset($alreadySend[$t->utente_id])) {
                    $alreadySend[$t->utente_id] = $t->utente_id;
                    $this->info('-------------------');
                    $this->info('Invio avviso topic: '.$t->utente->label);


                        $subject = 'C\'è un nuovo messaggio di topic';
                        $message = 'Hai ricevuto nuovi messaggi nel topic: ' . Str::title($t->topic->oggetto);
                        $message .= '<br>Accedi per leggere i nuovi messaggi inseriti';

                        $email = $t->utente->user->email;
                        if (config('app.debug')) {
                            $email = 'mimmomecca@gmail.com';
                        }
                        sendEmailGenerica($email , [], $subject, $message);

                }
            }
        }

        if (count($alreadySend)) {
            DB::table('messaggio_topic_notify')
                ->whereIn('utente_id', $alreadySend)
                ->update([
                    'sent_at' => \Carbon\Carbon::now()
                ]);
        }

    }
}
