<?php

namespace App\Events\Illuminate\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskAddedUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $old_ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $task, $old_ids)
    {
        $this->task = $task;
        $this->old_ids = $old_ids;
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
