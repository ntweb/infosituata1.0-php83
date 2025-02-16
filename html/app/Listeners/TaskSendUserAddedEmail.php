<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CreateNotification;
use App\Events\Illuminate\Events\TaskAddedUser;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class TaskSendUserAddedEmail
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
     * @param  TaskAddedUser  $event
     * @return void
     */
    public function handle(TaskAddedUser $event)
    {

        $users_ids = $event->task->users_ids ? json_decode($event->task->users_ids) : [];
        $old_ids = $event->old_ids;

        // Log::info($old_ids);
        // Log::info($users_ids);
        // Log::info('------------------------');

        $subject = 'Associazione a task: '. $event->task->label;

        $bcc = [];
        foreach ($users_ids as $user_id) {
            if (!in_array($user_id, $old_ids)) {
                $user = User::find($user_id);
                $bcc[] = $user->email;

                event(new CreateNotification($user->id, [
                    'module' => 'task',
                    'label' => $subject,
                    'route' => route('task.assegnati')
                ]));
            }
        }

        if (count($bcc)) {
            // Log::info('Inviooooo');

            $link = route('task.assegnati');

            $message = 'Avvenuta associazione al task : ' . $event->task->label;
            $message .= '<br>';
            $message .= 'Link ai task <a href="'.$link.'">'. $link .'</a>';

            sendEmailGenerica(null , $bcc, $subject, $message);
        }
    }
}
