<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CommessaNodeDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class DeleteScadenzaCommessa
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
     * @param  CommessaNodeDeleted  $event
     * @return void
     */
    public function handle(CommessaNodeDeleted $event)
    {
        $commessa = $event->node;
        DB::table('scadenze')->where('commesse_id', $commessa->id)->delete();
    }
}
