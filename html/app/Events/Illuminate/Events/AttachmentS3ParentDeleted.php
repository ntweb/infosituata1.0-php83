<?php

namespace App\Events\Illuminate\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AttachmentS3ParentDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reference_id, $reference_table;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reference_id, $reference_table)
    {
        $this->reference_id = $reference_id;
        $this->reference_table = $reference_table;
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
