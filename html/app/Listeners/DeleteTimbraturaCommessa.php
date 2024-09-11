<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\TimbraturaDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class DeleteTimbraturaCommessa
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
     * @param  TimbraturaDeleted  $event
     * @return void
     */
    public function handle(TimbraturaDeleted $event)
    {
        $t = $event->timbratura;

        /** In caso di squadrature cancello **/
        DB::table('commesse_log')
            ->where('in_timbrature_id', $t->id)
            ->orWhere('out_timbrature_id', $t->id)
            ->delete();

    }
}
