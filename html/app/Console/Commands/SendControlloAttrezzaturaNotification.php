<?php

namespace App\Console\Commands;

use App\Mail\CacciatoreAvviso;
use App\Mail\NotificaListe;
use App\Models\PrenotazioneDay;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PDF;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendControlloAttrezzaturaNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'controllo-attrezzatura:notify';

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
    protected $description = 'Invia notifica su controllo attrezzatura';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = \Carbon\Carbon::today()->toDateString();
        $messaggi = DB::table('messaggi')->where('module', 'manutenzione-attrezzatura')->whereNull('sent_at')->where('to_send', '1')->get();

        foreach ($messaggi as $m) {
            $this->info('-------------------');
            $this->info('Messaggio ID: '.$m->id);

            $bcc = [];
            $users = User::whereIn('utente_id', explode(',', $m->utenti_ids))->get();
            if (count($users)) {
                foreach ($users as $u) {
                    $bcc[] = $u->email;
                }
            }


            if (count($bcc)) {
                $this->info(join(',', $bcc));
                $mezzo =

                $subject = 'Nuovo controllo attrezzatura inserito';
                $message = route('controllo.edit', $m->manutenzioni_id);

                sendEmailGenerica(null , $bcc, $subject, $message);
            }

            DB::table('messaggi')->where('id', $m->id)->update([
                'sent_at' => \Carbon\Carbon::now(),
                'to_send' => '0'
            ]);

        }

    }
}
