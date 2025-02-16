<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\TimbraturaPermessoUpdated;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class EventTimbraturaPermessoSet
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
     * @param  TimbraturaPermessoUpdated  $event
     * @return void
     */
    public function handle(TimbraturaPermessoUpdated $event)
    {
        $permesso = $event->permesso;
        if ($permesso->status) {
            if ($permesso->status == 'accettato') {

                $u = User::find($permesso->users_id);

                $event = Evento::firstOrNew(['timbrature_permessi_id' => $permesso->id]);
                $event->azienda_id = $permesso->azienda_id;
                $event->items_id = $u->utente_id;
                $event->titolo = $permesso->type;
                $event->descrizione = $permesso->type;
                $event->start = $permesso->start_at;
                $event->end = $permesso->end_at ?? $permesso->start_at;
                $event->username = auth()->user()->name;
                $event->livello = 'basso';

                if ($permesso->type == 'permesso orario') {
                    $event->descrizione = 'Permesso dalle ' . dataOra($permesso->start_at) . ' alle ' . dataOra($permesso->end_at);
                }

                $event->save();
            }
            else if ($permesso->status == 'rifiutato') {
                DB::table('items_eventi')->where('timbrature_permessi_id', $permesso->id)->delete();
            }
        }
    }
}
