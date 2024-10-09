<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserEventSubscriber
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

    public function handleUserLogout(\Illuminate\Auth\Events\Logout $event) {
        // Log::info('handleUserLogout');
        $user = $event->user;
        $user->permission_checked_at = null;
        $user->save();
        // Log::info('handleUserLogout');
    }

    public function handleUserAuthenticated(\Illuminate\Auth\Events\Authenticated $event) {
        // Log::info('handleUserAuthenticated');
        $refresh_permissions = false;
        $check_permissions_every_minutes = 15;
        $user = $event->user;
        // Log::info('handleUserAuthenticated');

        if ($user->utente_id) {

            $now = \Carbon\Carbon::now();
            if (!$user->permission_checked_at) {
                $refresh_permissions = true;
            }

            if (!$refresh_permissions) {
                $last_refresh = new \Carbon\Carbon($user->permission_checked_at);
                $diff = $now->diffInMinutes($last_refresh);

                // Log::info('Diff: ' . $diff . ' now: ' . $now . ' last: ' . $last_refresh);
                if ($diff >= $check_permissions_every_minutes)
                    $refresh_permissions = true;
            }

            if (!$refresh_permissions) {
                // Log::info('Skip refresh permissions');
                return;
            }

            $utente = DB::table('items')->where('id', $user->utente_id)->first();
            session()->put('azienda_id', $utente->azienda_id);

            $user->permission_checked_at = $now;
            $user->save();

            // Log::info('Do refresh permissions');
            $gruppi_ids = DB::table('gruppo_utente')->where('utente_id', $user->utente_id)->get()->pluck('gruppo_id');

            // Inserire la logica per ricavare tutte le autorizzazioni dell'utente
            $permissions = [];
            foreach ($gruppi_ids as $grId) {
                $_permissions = DB::table('autorizzazioni')->whereJsonContains('gruppi_ids', $grId)->get();
                foreach ($_permissions as $_p) {
                    $permissions[] = $_p->permission;
                }
            }

            $user->gruppi_ids = json_encode($gruppi_ids);
            $user->permissions = json_encode(array_values(array_unique($permissions)));
            $user->save();

            session()->put('next_permission_control', \Carbon\Carbon::now()->addMinutes(1));
        }
    }

    public function subscribe($events) {
        $events->listen(
            \Illuminate\Auth\Events\Authenticated::class,
            [UserEventSubscriber::class, 'handleUserAuthenticated']
            // 'App\Listeners\UserEventSubscriber@handleUserAuthenticated'
        );

        $events->listen(
            \Illuminate\Auth\Events\Logout::class,
            [UserEventSubscriber::class, 'handleUserLogout']
            // 'App\Listeners\UserEventSubscriber@handleUserLogout'
        );
    }

}
