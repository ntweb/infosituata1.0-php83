<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CommessaNodeSottoFaseChangedCosti;
use App\Events\Illuminate\Events\CommessaRicalculateCosts;
use App\Models\Commessa;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CommessaRicalculateCostsListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommessaRicalculateCosts $event): void
    {
        $root_id = $event->root_id;
        // Log::info('CommessaRicalculateCostsListener ' . $root_id);
        $level2 = Commessa::where('root_id', $root_id)
            ->where('type', 'fase_lv_2')
            ->get();

        foreach ($level2 as $node) {
            event(new CommessaNodeSottoFaseChangedCosti($node));
        }
    }
}
