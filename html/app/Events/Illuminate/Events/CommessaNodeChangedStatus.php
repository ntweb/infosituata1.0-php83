<?php

namespace App\Events\Illuminate\Events;

use App\Models\Commessa;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommessaNodeChangedStatus
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $node;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Commessa $node)
    {
        $this->node = $node;
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
