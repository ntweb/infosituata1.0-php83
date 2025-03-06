<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CarburanteAddOrUpdEvent;
use App\Models\Cisterna;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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

        if ($evt['old_cisterne_id'] ) {
            Cisterna::where('id', $evt['old_cisterne_id'])->increment('livello_attuale', $evt['old_litri']);
        }

        if ($evt['cisterne_id'] ) {
            // Log::info('CarburanteAddOrUpdListener cisterne_id: ' . $evt['cisterne_id']);
            Cisterna::where('id', $evt['cisterne_id'])->decrement('livello_attuale', $evt['litri']);
        }
    }
}
