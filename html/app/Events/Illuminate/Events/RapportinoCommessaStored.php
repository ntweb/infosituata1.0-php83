<?php

namespace App\Events\Illuminate\Events;

use App\Models\Commessa;
use App\Models\CommessaRapportino;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RapportinoCommessaStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rapportino;
    public $fase;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CommessaRapportino $rapportino, Commessa $fase)
    {
        $this->rapportino = $rapportino;
        $this->fase = $fase;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
