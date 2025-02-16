<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function show($id) {
        $n = Notification::where('id', $id)
            ->where('users_id', auth()->user()->id)
            ->first();

        if (!$n)
            abort(404);

        if (!$n->opened_at) {
            $n->opened_at = \Carbon\Carbon::now();
            $n->save();
        }

        return response()->redirectTo($n->route);
    }
}
