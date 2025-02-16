<?php

namespace App\Console\Commands;

use App\Models\Parameter;
use Illuminate\Console\Command;
class ZohoRefreshToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:refresh-token';

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
        $zoho = Parameter::find('ZOHO_TOKEN');
        if ($zoho) {
            $jsonString = $zoho->value;
            $json = json_decode($jsonString, true);
            if ($json['error']) {
                $zoho->delete();
                $zoho = null;
            }
        }

        if (!$zoho) {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', 'https://accounts.zoho.eu/oauth/v2/token', [
                'form_params' => [
                    'code' => config('zoho.code'),
                    'grant_type' => 'authorization_code',
                    'client_id' => config('zoho.client_id'),
                    'client_secret' => config('zoho.client_secret'),
                ]
            ]);

            $statusCode = $res->getStatusCode();
            if ($statusCode == 200) {
                $jsonString = $res->getBody()->getContents();
                $json = json_decode($jsonString, true);

                if (!isset($json['error'])) {
                    $zoho = Parameter::firstOrNew(['id' => 'ZOHO_TOKEN']);
                    $zoho->value = $jsonString;
                    $zoho->save();
                }
                else {
                    $this->error($jsonString);
                }
            }
        }
        else {
            $jsonString = $zoho->value;
            $json = json_decode($jsonString, true);
            $refreshToken = $json['refresh_token'];

            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', 'https://accounts.zoho.eu/oauth/v2/token', [
                'form_params' => [
                    'scope' => 'Desk.tickets.ALL',
                    'grant_type' => 'refresh_token',
                    'client_id' => config('zoho.client_id'),
                    'client_secret' => config('zoho.client_secret'),
                    'refresh_token' => $refreshToken,
                ]
            ]);

            if (!isset($json['error'])) {
                $jsonString = $res->getBody()->getContents();
                $json = json_decode($jsonString, true);
                $json['refresh_token'] = $refreshToken;

                // $this->info(json_encode($json));
                $zoho->value = json_encode($json);
                $zoho->save();
            }
            else {
                $this->error($jsonString);
            }
        }





    }
}
