<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\TaskChangedDates;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskParentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TaskChangedDates  $event
     * @return void
     */
    public function handle(TaskChangedDates $event)
    {
        $node = $event->task;
        $root = $node->root;

        $otherTasks = DB::table('tasks')
            ->where('root_id', $node->root_id)
            ->get();

        $firstTask = $otherTasks
            ->filter(function ($t) {
                return $t->started_at != null;
            })
            ->sortBy('started_at')
            ->first();

        $lastTask = $otherTasks
            ->filter(function ($t) {
                return $t->completed_at != null;
            })
            ->sortBy('completed_at')
            ->last();

        $areAllTasksCompleted = $otherTasks->filter(function ($t) {
            return $t->completed_at != null;
        });

        if ($firstTask) {
            $root->started_at =  $firstTask->started_at;
        }

        $root->completed_at = null;
        if ($areAllTasksCompleted->count() == $otherTasks->count())
            $root->completed_at =  $lastTask->completed_at;

        $root->save();
    }
}
