<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CommessaNodeSottoFaseChangedDatePreviste;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ChangeNodeParentDate
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
        $tipo_data = $event->tipo_data;
        if ($node->type == 'fase_lv_2' || $node->type == 'fase_lv_1') {
            $parent = $node->parent;
            $siblings = $node->siblings()->get();


            switch ($tipo_data) {
                case 'effettiva':
                    /** Calcolo data inizio effettiva **/
                    $di = $_start_check = new \Carbon\Carbon($node->data_inizio_effettiva);
                    if ($siblings->count()) {
                        $di = $siblings->reduce(function($d, $item) use ($_start_check) {
                            $d = $d ?? $_start_check->startOfDay();
                            $_ = new \Carbon\Carbon($item->data_inizio_effettiva);
                            if ($d->lt($_->startOfDay())) {
                                return $d;
                            }
                            return $_->startOfDay();
                        });
                    }

                    /** Calcolo data fine effettiva **/
                    $df = $_start_check = new \Carbon\Carbon($node->data_fine_effettiva);
                    if ($siblings->count()) {
                        $df = $siblings->reduce(function($d, $item) use ($_start_check) {
                            $d = $d ?? $_start_check->startOfDay();
                            $_ = new \Carbon\Carbon($item->data_fine_effettiva);
                            if ($d->gt($_->startOfDay())) {
                                return $d;
                            }
                            return $_->startOfDay();
                        });
                    }

                    $parent->data_inizio_effettiva = $di;
                    $parent->data_fine_effettiva = $df;

                    // se c'è un fratello con date effettive non settate allora annullo anche quelle del parent
                    /** Per adesso disabilito questo controllo **/
                    /*
                    if ($siblings->count()) {
                        $dateEffNonSettate = $siblings->filter(function ($item) {
                            return !$item->data_inizio_effettiva;
                        });

                        if ($dateEffNonSettate->count()) {
                            $parent->data_inizio_effettiva = null;
                            $parent->data_fine_effettiva = null;
                        }
                    }
                    */

                    break;
                default:
                    /** Calcolo data inizio prevista **/
                    $di = $_start_check = new \Carbon\Carbon($node->data_inizio_prevista);
                    if ($siblings->count()) {
                        $di = $siblings->reduce(function($d, $item) use ($_start_check) {
                            $d = $d ?? $_start_check->startOfDay();
                            $_ = new \Carbon\Carbon($item->data_inizio_prevista);
                            if ($d->lt($_->startOfDay())) {
                                return $d;
                            }
                            return $_->startOfDay();
                        });
                    }

                    /** Calcolo data fine prevista **/
                    $df = $_start_check = new \Carbon\Carbon($node->data_fine_prevista);
                    if ($siblings->count()) {
                        $df = $siblings->reduce(function($d, $item) use ($_start_check) {
                            $d = $d ?? $_start_check->startOfDay();
                            $_ = new \Carbon\Carbon($item->data_fine_prevista);
                            if ($d->gt($_->startOfDay())) {
                                return $d;
                            }
                            return $_->startOfDay();
                        });
                    }

                    $parent->data_inizio_prevista = $di;
                    $parent->data_fine_prevista = $df;
            }

            $parent->save();

            // Log::info($di);
            // Log::info($df);
        }
    }
}
