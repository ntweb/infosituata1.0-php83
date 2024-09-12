<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CommessaNodeSottoFaseChangedDateEffettive;
use App\Models\Commessa;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckNodeRitardo
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

    public function handle($event)
    {
        $node = $event->node;
        // Log::info($node->id);
        // $node->save();

        $nodes = Commessa::where(function ($query) use ($node) {
                $query
                    ->where('id', $node->root_id)
                    ->orWhere('root_id', $node->root_id);
            })
            ->whereNull('item_id')
            ->get();

        $exist_ritardo_commessa = false;
        foreach ($nodes as $n) {
            // if ($n->id == 78){
            //    Log::info('checking.. ' .$n->label);
            // }
            $n->fl_ritardo = '0';
            if ($n->data_inizio_prevista && $n->data_inizio_effettiva) {
                $dip = new \Carbon\Carbon($n->data_inizio_prevista);
                $die = new \Carbon\Carbon($n->data_inizio_effettiva);

                $dfp = new \Carbon\Carbon($n->data_fine_prevista);
                $dfe = new \Carbon\Carbon($n->data_fine_effettiva);

                /*
                if ($n->id == 78){
                    Log::info('inizio pre ' .$dip);
                    Log::info('inizio eff ' .$die);
                    Log::info('fine pre ' .$dfe);
                    Log::info('fine eff ' .$dfp);
                }
                */

                //if ($die->gt($dip) || $dfe->gt($dfp)) {
                if ($dfe->gt($dfp)) {
                    if ($n->type == 'fase_lv_1') {
                        $exist_ritardo_commessa = true;
                    }

                    $n->fl_ritardo = '1';
                }
            }
            $n->save();
        }

        /** La commessa ha ritardo solo se i suoi figli diretti hanno un ritardo **/
        DB::table('commesse')->where('id', $node->root_id)
            ->update([
                'fl_ritardo' => $exist_ritardo_commessa ? '1' : '0'
        ]);

    }
}
