<?php

namespace App\Console\Commands;

use App\Models\MessaggioWhatsapp;
use Illuminate\Console\Command;

class SendWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send';

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
    protected $description = 'Invia i messaggi whatsapp broadcast';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $messaggi = MessaggioWhatsapp::whereNull('sent_at')->with(['messaggio', 'messaggio.azienda', 'utente'])->get();
        foreach ($messaggi as $m) {
            $this->info('sending whatsapp to -> ' . $m->utente->label);
            // $this->info('sending whatsapp to -> ' . $m->messaggio);
            try {
                $telephone = trim($m->utente->extras6);
                if (config('app.debug')) {
                    $telephone = '393282519247';
                    // $telephone = '393474005425';
                }

                if ($telephone) {
                    $res = sendWhatsappMessage($m->messaggio->azienda, $telephone, $m->message);

                    $m->sent_at = \Carbon\Carbon::now();
                    $m->wamid = $res->messages[0]->id;
                    $m->save();
                }
            }
            catch (\Exception $e) {
                $this->info('Error sending whatsapp: ' . $e->getMessage());
            }
        }

    }
}
