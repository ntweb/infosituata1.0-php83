<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CreateNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class CreateNotificationListener
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
     * @param  CreateNotification  $event
     * @return void
     */
    public function handle(CreateNotification $event)
    {
        $users_id = $event->users_id;
        $parameters = $event->parameters;

        $uuid = Str::uuid();
        $azienda_id = getAziendaId();

        $n = new \App\Models\Notification;
        $n->id = $uuid;
        $n->azienda_id = $azienda_id;
        $n->users_id = $users_id;

        foreach ($parameters as $k => $v) {
            $n->$k = $v;
        }

        $n->save();
    }
}
