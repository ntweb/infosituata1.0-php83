<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }



    /**
 * Show the application dashboard.
 *

     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
 */

    public function index(Request $request)

    {

        if (Gate::denies('privacy-accepted'))
            return redirect()->route('privacy.create');

        if ($request->input('fs', 'all') == 'me') {
            session()->put('fs', true);
        }
        else {
            session()->forget('fs');
        }

        $data['inScadenza'] = getInscadenza();
        $data['inScadenza'] =  $data['inScadenza']->sortBy(function ($row, $key) {
            return scadeTra($row);
        });

        $data['scaduti'] = getScaduti();
        $data['scaduti'] =  $data['scaduti']->sortBy(function ($row, $key) {
            return scadeTra($row);
        });


        $data['small'] = true;

        // lista dei messaggi
        $data['messages'] = [];

        if (Auth::user()->utente_id) {
            $data['messages'] = MessaggioUtente::whereUtenteId(Auth::user()->utente_id)
                ->whereHas('messaggio', function($query) {
                    $query->where('module', 'messaggio');
                })
                ->with(['messaggio', 'messaggio.user'])
                ->orderBy('created_at', 'desc')
                ->limit(50)->get();
        }

        $data['tasks'] = $this->_taskAssegnati();

        return view('dashboard.index', $data);
    }

    private function _taskAssegnati()
    {
        if (Gate::allows('is-tasks-module-enabled')) {

            $list = Task::whereNotNull('parent_id')
                ->whereNull('completed_at')
                ->whereJsonContains('users_ids', [\auth()->user()->id.""])
                ->with("root.cliente")
                ->orderBy('created_at', 'desc')
                ->paginate(500)
                ->appends(request()->query());

            return $list;
        }
        return [];
    }

}

