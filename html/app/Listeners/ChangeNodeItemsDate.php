<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CommessaNodeSottoFaseChangedDatePreviste;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ChangeNodeItemsDate
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
     * @param  CommessaNodeSottoFaseChangedDatePreviste  $event
     * @return void
     */
    public function handle(CommessaNodeSottoFaseChangedDatePreviste $event)
    {
        $node = $event->node;
        DB::table('commesse')
            ->where('parent_id', $node->id)
            ->whereNotNull('item_id')
            ->where('data_inizio_prevista', '<', $node->data_inizio_prevista)
            ->update([
                'data_inizio_prevista' => $node->data_inizio_prevista
            ]);

        DB::table('commesse')
            ->where('parent_id', $node->id)
            ->whereNotNull('item_id')
            ->where('data_fine_prevista', '>', $node->data_fine_prevista)
            ->update([
                'data_fine_prevista' => $node->data_fine_prevista
            ]);
    }
}
