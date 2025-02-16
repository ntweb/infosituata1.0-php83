<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CommessaNodeSottoFaseChangedCosti;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ChangeNodeParentCosti
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
     * @param  CommessaNodeSottoFaseChangedCosti  $event
     * @return void
     */
    public function handle(CommessaNodeSottoFaseChangedCosti $event)
    {
        $node = $event->node;
        if ($node->type == 'fase_lv_2') {
            $parent = $node->parent;
            $siblings = $node->siblings()->get();


            /** costo previsto **/
            $costo = $siblings->reduce(function($d, $item)  {
                return $d + $item->costo_previsto;
            });
            $costo = $costo + $node->costo_previsto;

            /** costo effettivo **/
            $costo_effettivo = $siblings->reduce(function($d, $item)  {
                return $d + $item->costo_effettivo;
            });
            $costo_effettivo = $costo_effettivo + $node->costo_effettivo;

            /** prezzo cliente **/
            $prezzo = $siblings->reduce(function($d, $item)  {
                return $d + $item->prezzo_cliente;
            });
            $prezzo = $prezzo + $node->prezzo_cliente;


            $parent->costo_previsto = $costo;
            $parent->costo_effettivo = $costo_effettivo;
            $parent->prezzo_cliente = $prezzo;
            $parent->save();
        }

        /** calcolo totali commessa **/
        $root = $node->root;
        $nodes_lv_1 = DB::table('commesse')->where('type', 'fase_lv_1')->where('parent_id', $node->root_id)->get();
        $cp = 0;
        $ce = 0;
        $pc = 0;
        foreach ($nodes_lv_1 as $n) {
            $cp = $cp + $n->costo_previsto;
            $ce = $ce + $n->costo_effettivo;
            $pc = $pc + $n->prezzo_cliente;
        }

        $root->costo_previsto = $cp;
        $root->costo_effettivo = $ce;
        $root->prezzo_cliente = $pc;
        $root->save();
    }
}
