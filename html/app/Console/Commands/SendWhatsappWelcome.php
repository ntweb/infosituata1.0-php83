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

class SendWhatsappWelcome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send-welcome';

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
    protected $description = 'Invia il messaggio di welcome per cominciare l\'interazione';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = \App\Models\Utente::where('whatsapp_send_welcome', '1')->with(['azienda', 'user']);
        if (config('app.debug')) {
            $query = $query->limit(1);
        }
        $utenti = $query->get();

        foreach ($utenti as $u) {
            $this->info('sending whatsapp welcome to -> ' . $u->label);
            try {

                $telephone = trim($u->extras6);
                if (config('app.debug')) {
                    $telephone = '393282519247';
                    // $telephone = '393474005425';
                }
                else {
                    $u->whatsapp_send_welcome = '0';
                }

                if ($telephone && ($u->user->active || config('app.debug'))) {

                    $endpoint = $u->azienda->module_whatsapp_endpoint;
                    $token = $u->azienda->module_whatsapp_token;

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$endpoint . 'messages');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);

                    $message = [
                        'messaging_product' => 'whatsapp',
                        'recipient_type' => 'individual',
                        'to' => $telephone,
                        'type' => 'template',
                        'template' => [
                            'name' =>  'welcome',
                            'language' => ['code' => 'it'],
                            'components' => [
                                [
                                    'type' => 'body',
                                    'parameters' => [
                                        [
                                            'type' => 'text',
                                            'text' => $u->azienda->label
                                        ]
                                    ]
                                ]
                            ]
                        ],
                    ];

                    $this->info(json_encode($message));

                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

                    $headers = array();
                    $headers[] = 'Authorization: Bearer ' . $token;
                    $headers[] = 'Content-Type: application/json';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        $this->info('Error:' . curl_error($ch));
                    }

                    $this->info($result);
                    curl_close ($ch);

                    if (!config('app.debug')) {
                        $u->save();
                    }
                }
            }
            catch (\Exception $e) {
                $this->info('Error sending whatsapp: ' . $e->getMessage());
            }
        }

    }
}
