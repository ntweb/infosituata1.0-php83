<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CommessaNodeDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class RecalculateParent
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
        $node = $event->node;
        // Log::info($node);
        if ($node->type == 'fase_lv_2') {
            // $parent = \App\Models\Commessa::find($node->parent_id);
            $parent = $node->parent;
            $siblings = $node->siblings()->get();

            if ($siblings->count()) {
                /** Calcolo data inizio prevista **/
                $di = $siblings->reduce(function($d, $item) {
                    $_ = new \Carbon\Carbon($item->data_inizio_prevista);
                    if ($d) {
                        if ($d->lt($_->startOfDay())) {
                            return $d;
                        }
                    }
                    return $_->startOfDay();
                });

                /** Calcolo data fine prevista **/
                $df = $siblings->reduce(function($d, $item) {
                    $_ = new \Carbon\Carbon($item->data_fine_prevista);
                    if ($d) {
                        if ($d->gt($_->startOfDay())) {
                            return $d;
                        }
                    }
                    return $_->startOfDay();
                });

                /** costo previsto **/
                $costo = $siblings->reduce(function($d, $item)  {
                    return $d + $item->costo_previsto;
                });

                /** prezzo cliente **/
                $prezzo = $siblings->reduce(function($d, $item)  {
                    return $d + $item->prezzo_cliente;
                });
            }
            else {
                // non esistono figli e risetto i flag
                $parent->fl_is_status_changeble = '1';
                $parent->fl_is_data_prevista_changeble = '1';
                $parent->fl_is_costo_changeble = '1';
                $parent->fl_can_have_sottofase = '1';
                $parent->fl_can_have_item = '1';
            }

            if (isset($di)) {
                $parent->data_inizio_prevista = $di;
                $parent->data_fine_prevista = $df;


                $parent->costo_previsto = $costo;
                $parent->prezzo_cliente = $prezzo;

            }

            $parent->save();

        }
    }
}
