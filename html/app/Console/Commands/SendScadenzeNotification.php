<?php

namespace App\Console\Commands;

use App\Models\Scadenza;
use App\Models\Utente;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class SendScadenzeNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scadenze:notify';

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
    protected $description = 'Invia notifica su scadenze in arrivo';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = \Carbon\Carbon::today()->toDateString();
        $scadenze = Scadenza::whereAdviceAt($today)
                        ->whereAdviced('0')
                        ->limit(10)
                        ->with(['gruppi', 'item', 'module', 'detail', 'commessa'])->get();

        $scadenzeCommesse = $scadenze->filter(function ($s) {
            return $s->commesse_id !== 0;
        });

        $scadenze = $scadenze->filter(function ($s) {
            return $s->item_id !== 0;
        });

        foreach ($scadenze as $scadenza) {
            $this->info('-------------------');
            $this->info('Scadenza ID: '.$scadenza->id);

            $scadenza->adviced = '1';
            $scadenza->save();

            $bcc = [];
            if ($scadenza->gruppi) {
                foreach ($scadenza->gruppi as $gruppo) {
                    if($gruppo->utenti) {
                        foreach ($gruppo->utenti as $utente) {
                            if ($utente->user->active)
                                $bcc[] = $utente->user->email;
                        }
                    }
                }
            }

            if ($scadenza->advice_item == '1') {
                $utente = Utente::find($scadenza->item_id);
                    if ($utente->user->active)
                        $bcc[] = $utente->user->email;
            }

            if (count($bcc)) {
                $this->info(join(',', $bcc));
                $subject = 'Avviso scadenza: ' . Str::title($scadenza->item->label);
                $message = Str::title($scadenza->item->label);
                $message .= '<br>'.$scadenza->module->label.' / '.$scadenza->detail->label;
                $message .= '<br>Data scadenza: '.data($scadenza->end_at);

                sendEmailGenerica(null , $bcc, $subject, $message);
            }

        }

        foreach ($scadenzeCommesse as $scadenza) {
            $this->info('-------------------');
            $this->info('Scadenza commessa ID: '.$scadenza->id);

            $scadenza->adviced = '1';
            $scadenza->save();

            $bcc = [];
            if ($scadenza->gruppi) {
                foreach ($scadenza->gruppi as $gruppo) {
                    if($gruppo->utenti) {
                        foreach ($gruppo->utenti as $utente) {
                            if ($utente->user->active)
                                $bcc[] = $utente->user->email;
                        }
                    }
                }
            }

            if (count($bcc)) {
                $this->info(join(',', $bcc));
                $subject = 'Avviso scadenza: ' . Str::title($scadenza->commessa->label);
                $message = Str::title($scadenza->label);
                $message .= '<br>'.$scadenza->description;
                $message .= '<br>Data scadenza: '.data($scadenza->end_at);

                sendEmailGenerica(null , $bcc, $subject, $message);
            }

        }

    }
}
