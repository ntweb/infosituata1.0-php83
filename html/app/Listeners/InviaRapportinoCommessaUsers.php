<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\RapportinoCommessaStored;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviaRapportinoCommessaUsers
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
     * @param  RapportinoCommessaStored  $event
     * @return void
     */
    public function handle(RapportinoCommessaStored $event)
    {
        $rapportino = $event->rapportino;
        $fase = $event->fase;

        $link = action('Dashboard\CommessaRapportinoController@show', $rapportino->id);

        $bcc = App\Models\User::whereIn('id', json_decode($rapportino->send_to_ids))->get()->pluck('email', 'email');

        $subject = 'Rapportino Commessa: '. $fase->root->label .' fase: ' . $fase->label;

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
