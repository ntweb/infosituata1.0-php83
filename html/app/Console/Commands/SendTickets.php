<?php

namespace App\Console\Commands;

use App\Models\Parameter;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;

class SendTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:send';

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
    protected $description = 'Effettua il refresh token di Zoho';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        /**
        $zoho = Parameter::find('ZOHO_TOKEN');
        // $this->info('ZOHO_TOKEN', $zoho->value);
        if ($zoho->value) {
            $json = json_decode($zoho->value, true);
        **/

            $tickets = Ticket::orderBy('created_at')->limit(10)->get();
            foreach ($tickets as $t) {

                $attachmentURL = null;
                $attachment = 'tickets/'.$t->id.'.png';
                if (Storage::disk('public')->exists($attachment)) {
                    $attachmentURL = route('ticket.attachment', $t->id);
                }

                $message = nl2br($t->descrizione);
                $message .= "<br>-------------------<br>";
                $message .= $t->azienda."<br>";
                $message .= 'Utente: ' .$t->utente."<br>";
                $message .= 'Modulo: ' . $t->modulo.'<br>';
                $message .= 'URL: <a href="'.$t->url.'">Apri URL catturata</a><br>';

                if ($attachmentURL) {
                    $message .= 'Attachment: <a href="'.$attachmentURL.'">Apri allegato</a><br>';
                }

                /*
                $data = [
                    'departmentId' => 138575000000007061,
                    'subject' => $t->oggetto,
                    'contact' => [
                        'email' => strtolower($t->email),
                        'lastName' => strtoupper($t->utente),
                    ],
                    'email' => strtolower($t->email),
                    'description' => $description,
                    'category' => $t->modulo,
                    'channel' => 'Infosituata web application',
                    'webUrl' => $t->url
                ];
                */

                sendEmailGenerica(null, ['mimmomecca@gmail.com', 'info@infosituata.com'], 'Ticket: ' . $t->oggetto, $message);

                /**
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://desk.zoho.eu/api/v1/tickets');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                $headers = array();
                $headers[] = 'Authorization: Zoho-oauthtoken ' . $json['access_token'];
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    $this->error('Error:' . curl_error($ch));
                }
                else {
                    // $this->info($result);
                    $t->delete();
                }

                curl_close($ch);
                **/

                $t->delete();
            }

        /**
        }
         **/
    }
}
