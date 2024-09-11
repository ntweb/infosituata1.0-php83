<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\RapportinoStored;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviaRapportinoUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RapportinoStored  $event
     * @return void
     */
    public function handle(RapportinoStored $event)
    {
        $rapportino = $event->rapportino;

        $link = action('Dashboard\RapportiniController@show', $rapportino->id);

        $bcc = App\Models\User::whereIn('id', json_decode($rapportino->send_to_ids))->get()->pluck('email', 'email');

        $subject = 'Rapportino generato: '. $rapportino->titolo;

        $message = 'Oggetto: ' . $rapportino->titolo;
        $message .= '<br>';
        $message .= '<br>';
        $message .= nl2br($rapportino->descrizione);
        $message .= '<br>';
        $message .= '<br>';
        $message .= 'Redatto da: ' . $rapportino->username;
        $message .= '<br>';
        $message .= 'Link al rapportino <a href="'.$link.'">'. $link .'</a>';

        sendEmailGenerica(null , $bcc, $subject, $message);
    }
}
