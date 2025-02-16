<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CommessaNodeChangedStatus;
use App\Exceptions\CommessaNodeException;
use App\Models\CommessaLog;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChangeNodeParentStatus
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
     * @param  CommessaNodeChangedStatus  $event
     * @return void
     */
    public function handle(CommessaNodeChangedStatus $event)
    {
        $node = $event->node;

        // controllo la dipendenza
        if ($node->execute_after_id) {
            $executeAfter = $node->executeAfter;
            if ($executeAfter->stato !== 'terminata') {
                throw new CommessaNodeException("Non è possibile cambiare lo stato fino a quando non risulta terminata la fase: ".$executeAfter->label);
            }
        }


        // controllo se i fratelli hanno tutti lo stesso stato

        $parent = $node->parent;
        $children = $parent->children;

        if (!$node->item_id && $parent->type) {
            $group = $children->groupBy('stato');

            if (count($group) > 1) {
                $parent->stato = 'nd';
            }
            else {
                $parent->stato = $node->stato;
            }
            $parent->save();

            /** Log del cambiamento **/
            $cl = new CommessaLog;
            $cl->id = Str::uuid();
            $cl->commesse_id = $parent->id;
            $cl->stato = $parent->stato;
            $cl->note = 'Stato cambiato in seguito alla modifica di ' . $node->label . ' ['.$node->stato.']';
            $cl->username = auth()->user()->name;
            $cl->save();
        }


        $root = $node->root;
        $bcc = [];
        if ($root->notification_users_ids) {
            $bcc = User::whereIn('id', json_decode($root->notification_users_ids))->get()->pluck('email', 'email');
        }

        if (count($bcc)) {
            $link = route('commessa.show', $root->id);
            $subject = 'Cambio stato lavorazione - Commessa: '. $root->label .' fase: ' . $node->label;
            $message = 'La fase / sottofase : ' . $node->label . ' è passata allo stato di lavorazione ' . $node->stato;
            $message .= '<br>';
            $message .= '<br>';
            $message .= 'Stato cambiato da: ' . auth()->user()->name;
            $message .= '<br>';
            $message .= 'Link alla commessa <a href="'.$link.'">'. $link .'</a>';
            sendEmailGenerica(null , $bcc, $subject, $message);
        }

    }
}
