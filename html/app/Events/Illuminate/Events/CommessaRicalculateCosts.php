<?php

namespace App\Events\Illuminate\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommessaRicalculateCosts
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $root_id;

    /**
     * Create a new event instance.
     */
    public function __construct($root_id)
    {
        $this->root_id = $root_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
