<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CommessaNodeInserted;
use App\Events\Illuminate\Events\CreateNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ChangeNodeParentFlags
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
     * @param  CommessaNodeInserted  $event
     * @return void
     */
    public function handle(CommessaNodeInserted $event)
    {
        $node = $event->node;
        $parent = $node->parent;

        if ($node->item_id) {
            $node->fl_is_status_changeble = '0';
            $node->fl_is_data_prevista_changeble = '1';
            $node->fl_can_have_sottofase = '0';
            $node->fl_can_have_item = '0';

            $node->data_inizio_prevista = $parent->data_inizio_prevista;
            $node->data_fine_prevista = $parent->data_fine_prevista;

            if ($node->type == 'materiale') {
                $node->fl_is_data_prevista_changeble = '0';
                $node->data_inizio_prevista = null;
                $node->data_fine_prevista = null;
            }
            else {
                $node->costo_item_giornaliero_previsto = $node->day_to_hours * $node->costo_item_orario_previsto;
            }

            if ($parent->type) {
                // il genitore non è la commessa
                $parent->fl_is_status_changeble = '1';
                $parent->fl_is_data_prevista_changeble = '1';
                $parent->fl_can_have_sottofase = '0';
                $parent->fl_can_have_item = '1';
            }
        }
        else {

            // fase o sottofase
            $node->fl_is_status_changeble = '1';
            $node->fl_is_data_prevista_changeble = '1';
            $node->fl_is_costo_changeble = '1';
            $node->fl_can_have_sottofase = '1';
            $node->fl_can_have_item = '1';

            if ($parent->type) {
                // il genitore non è la commessa
                $parent->fl_is_status_changeble = '0';
                $parent->fl_is_data_prevista_changeble = '0';
                $parent->fl_is_costo_changeble = '0';
                $parent->fl_can_have_sottofase = '1';
                $parent->fl_can_have_item = '0';

                $siblings = $node->siblings()->get();
                $costo = $siblings->reduce(function($d, $item) {
                    return $d + $item->costo_previsto;
                });
                $prezzo = $siblings->reduce(function($d, $item) {
                    return $d + $item->prezzo_cliente;
                });

                $node->costo_previsto = $parent->costo_previsto - $costo;
                $node->prezzo_cliente = $parent->prezzo_cliente - $prezzo;
            }



            $node->data_inizio_prevista = $parent->data_inizio_prevista;
            $node->data_fine_prevista = $parent->data_fine_prevista;

        }

        $node->save();

        if ($parent)
            $parent->save();

        if ($node->type == 'utente') {
            $root = $node->root;
            $user = App\Models\User::where('utente_id', $node->item_id)->first();

            if ($user) {

                $subject = 'Associazione a fase / sottofase - Commessa: '. $root->label .' fase: ' . $parent->label;

                if ($root->fl_send_email_association) {
                    $bcc = [$user->email];
                    $link = action('Dashboard\CommessaController@show', $root->id);

                    $message = 'È stato associato alla fase '. $parent->label;
                    $message .= ' della commessa '. $root->label;
                    $message .= '<br>';
                    $message .= '<br>';
                    $message .= 'Link alla commessa <a href="'.$link.'">'. $link .'</a>';

                    sendEmailGenerica(null , $bcc, $subject, $message);
                }

                event(new CreateNotification($user->id, [
                    'module' => 'commessa',
                    'label' => $subject,
                    'route' => action('Dashboard\CommessaController@edit', [$root->id])
                ]));
            }

        }

        return $node;
    }
}
