<?php

namespace App\Events\Illuminate\Events;

use App\Models\TimbraturaPermesso;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TimbraturaPermessoUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $permesso;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TimbraturaPermesso $permesso)
    {
        $this->permesso = $permesso;
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
