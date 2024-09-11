<?php

namespace App\Events\Illuminate\Events;

use App\Models\Rapportino;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RapportinoStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rapportino;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Rapportino $rapportino)
    {
        $this->rapportino = $rapportino;
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
