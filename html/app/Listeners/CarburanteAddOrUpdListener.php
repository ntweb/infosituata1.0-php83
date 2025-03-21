<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CarburanteAddOrUpdEvent;
use App\Models\Cisterna;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CarburanteAddOrUpdListener
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
    public function handle(CarburanteAddOrUpdEvent $event): void
    {
        $evt = $event->el;
        Log::info('CarburanteAddOrUpdListener: ' . json_encode($evt));

        if ($evt['old_cisterne_id'] ) {
            Cisterna::where('id', $evt['old_cisterne_id'])->increment('livello_attuale', $evt['old_litri']);
        }

        if ($evt['cisterne_id']) {
            // Log::info('CarburanteAddOrUpdListener cisterne_id: ' . $evt['cisterne_id']);
            Cisterna::where('id', $evt['cisterne_id'])->decrement('livello_attuale', $evt['litri']);
        }

        if ($evt['cisterne_id']) {
            $checkLimit = Cisterna::where('id', $evt['cisterne_id'])->first();
            if ($checkLimit->livello_attuale <= $checkLimit->livello_minimo) {
                // parte avviso
                $gruppi_ids = $checkLimit->gruppi_ids ? json_decode($checkLimit->gruppi_ids) : [];
                if (count($gruppi_ids)) {
                    $emails = fromGruppiIdsToUserEmail($gruppi_ids);
                    $message = 'Il livello della cisterna '.$checkLimit->label.' è al di sotto del minimo consentito';
                    $message .= '<br>Livello attuale: '.$checkLimit->livello_attuale;
                    sendEmailGenerica(null, $emails, 'Alert livello cisterna', $message);
                }
            }
        }

    }
}
