<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\TaskAddedUser;
use App\Events\Illuminate\Events\TaskChangedDates;
use App\Models\Gruppo;
use App\Models\Task;
use App\Models\User;
use App\Models\Utente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TaskNodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Log::info($request->all());
        $parent = Task::whereNull('parent_id')->where('id', $request->input('node'))->first();
        if (!$parent) abort('404');

        $data['parent'] = $parent;

        $utentiIds = Utente::get()->pluck('id', 'id');
        $data['users'] = User::whereIn('utente_id', $utentiIds)->select('id', 'name as label')->get()->pluck('label', 'id');
        $data['usersSelected'] = [];

        /**
         * Ripropongo gli utenti dell'ultimo task inserito
         */
        $lastTask = Task::where('root_id', $parent->id)->orderBy('created_at', 'desc')->first();
        if ($lastTask) {
            $data['usersSelected'] = $lastTask->users_ids ? array_flip(json_decode($lastTask->users_ids)) : [];
        }

        $data['title'] = 'Crea nuovo task';
        $data['sub_title'] = null;
        $data['action'] = route('task-node.store', ['_parent_id' => $parent->id]);
        return view('dashboard.tasks.modals.create-node', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Log::info($request->all());

        $parent = Task::find($request->input('_parent_id'));
        if (!$parent) abort('404');


        $uuid = Str::uuid();

        $el = new Task;
        $el->root_id = $parent->id;

//        Log::info($uuid);
//        Log::info($el->root_id);
//        Log::info('----------------------------');

        switch ($request->input('_module', null)) {
            default:
                $validationRules = ['label' => 'required'];
                $validatedData = $request->validate($validationRules);

                $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
                foreach ($fields as $k => $v) {
                    $el->$k = $v;
                }
        }

        try {
            $el->id = $uuid;
            $el->azienda_id = $parent->azienda_id;

            $ids = json_encode($request->input('users_ids', []));
            $el->users_ids = $ids;

            $el->appendToNode($parent)->save();

            event(new TaskChangedDates($el));
            event(new TaskAddedUser($el, []));

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data['el'] = Task::find($id);
        if (!$data['el']) abort('404');

        if ($request->has('delete')) {
            $data['title'] = 'Eliminazione: ' . $data['el']->label;
            $data['action'] = route('task-node.destroy', $id);
            return view('dashboard.tasks.modals.delete-node', $data);
        }


        $data['siblings'] = $data['el']->siblings()->get();
        $data['siblings'] = $data['siblings']->pluck('label', 'id');

        $data['siblings']->prepend('-', '');

        // $data['dependent_node'] = Commessa::where('execute_after_id', $id)->get()->pluck('id', 'id');

//        Log::info($data['siblings']);
//        Log::info($data['dependent_node']);

//        if ($data['dependent_node']->count()) {
//            $data['siblings'] = $data['siblings']->filter(function($label, $id) use ($data) {
//                return $data['dependent_node']->contains(function($value, $key) use ($id) {
//                    // Log::info($key .' '. $id);
//                    return $key !== $id;
//                });
//            });
//        }

        $utentiIds = Utente::get()->pluck('id', 'id');
        $data['users'] = User::whereIn('utente_id', $utentiIds)->select('id', 'name as label')->get()->pluck('label', 'id');
        $data['usersSelected'] = $data['el']->users_ids ? array_flip(json_decode($data['el']->users_ids)) : [];


        $data['title'] = 'Modifica elemento';
        $data['action'] = route('task-node.update', $id);

        if ($request->has('_operator'))
            return view('dashboard.tasks.modals.create-operator-node', $data);

        return view('dashboard.tasks.modals.create-node', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $el = Task::with('root', 'parent')->find($id);
        if (!$el) abort('404');

        $old_ids = $el->users_ids ? json_decode($el->users_ids) : [];


        // Log::info($request->all());
        switch ($request->input('_module', null)) {
            case 'note':
                $validationRules = [];
                break;
            default:
                $validationRules = [
                    'label' => 'required',
                    'data_inizio_prevista' => 'sometimes|nullable|date',
                    'data_fine_prevista' => 'sometimes|nullable|date|after:data_inizio_prevista',
                    'started_at' => 'sometimes|nullable|date',
                    'completed_at' => 'sometimes|nullable|date|after:started_at',
                ];
        }

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_note']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                default:
                    $el->$k = $v;
            }
        }

        try {

            if (!$request->has('_module')) {
                $ids = json_encode($request->input('users_ids', []));
                $el->users_ids = $ids;
            }



            $el->save();

            if ($el->root_id) {
                event(new TaskChangedDates($el));
                event(new TaskAddedUser($el, $old_ids));
            }

            DB::commit();
            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {
            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $el = Task::find($id);
        if (!$el) abort(404);

        try {
            $el->delete();
            $payload = 'Cancellazione avvenuta correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di cancellazione!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function move($id, $versus) {
        $node = Task::find($id);
        if (!$node) abort('404');

        try {
            if ($versus == 'up')
                $node->up();
            else
                $node->down();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function started($id) {
        $node = Task::find($id);
        if (!$node) abort('404');

        try {
            $node->started_at = \Carbon\Carbon::now();
            $node->save();

            event(new TaskChangedDates($node));

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function completed($id) {
        $node = Task::with('root')->find($id);
        if (!$node) abort('404');

        try {
            $node->completed_at = \Carbon\Carbon::now();

            $node->save();
            event(new TaskChangedDates($node));

            /** Invio email notifica ai gruppi **/
            if ($node->root->fl_notify_task_completed && $node->root->notify_gruppi_ids) {

                $bcc = [];
                $gruppi = json_decode($node->root->notify_gruppi_ids);
                foreach ($gruppi as $gruppoId) {
                    $gruppo = Gruppo::with('utenti')->find($gruppoId);
                    if($gruppo->utenti) {
                        foreach ($gruppo->utenti as $utente) {
                            if ($utente->user->active)
                                $bcc[] = $utente->user->email;
                        }
                    }
                }

                // Log::info($bcc);
                if (count($bcc)) {
                    $subject = 'Attività completata: ' . Str::title($node->label);
                    $message = Str::title($node->label);
                    $message .= '<br>Attività segnata come completata da: '. auth()->user()->name;
                    $message .= '<br>Data completamento: '.dataOra($node->completed_at);
                    sendEmailGenerica(null , $bcc, $subject, $message);
                }
            }

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function note($id)
    {
        $data['node'] = Task::find($id);
        if (!$data['node']) abort('404');

        $data['title'] = 'Aggiungi note al task';
        $data['sub_title'] = null;
        $data['action'] = route('task-node.update', $id);

        return view('dashboard.tasks.modals.note-node', $data);
    }
}
