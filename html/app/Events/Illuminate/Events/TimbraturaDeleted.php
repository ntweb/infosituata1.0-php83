<?php

namespace App\Events\Illuminate\Events;

use App\Models\Timbratura;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TimbraturaDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timbratura;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Timbratura $timbratura)
    {
        $this->timbratura = $timbratura;
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
