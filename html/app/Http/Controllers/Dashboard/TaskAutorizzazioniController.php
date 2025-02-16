<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Task;
use App\Models\TaskAutorizzazione;
use App\Models\User;
use App\Models\Utente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskAutorizzazioniController extends Controller
{

    protected $controllers = [
        'task_mod_anagrafica' => [
            'label' => 'Modifica anagrafica task',
            'description' => 'Aggiorna i dati di anagrafica task, date previste, tags.'
        ],
        'task_mod_fasi' => [
            'label' => 'Modifica lista attività',
            'description' => 'Inserisci, modifica e cancella attività'
        ],
        /**  Per ora le seguenti voci non sono abilitate **/
        /**
        'task_mod_extra_fields' => [
            'label' => 'Modifica extra field task',
            'description' => 'Inserisci, modifica e cancella extra fields dei task'
        ],
        'task_update_extra_fields' => [
            'label' => 'Aggiornamento extra field dei task',
            'description' => 'Aggiornamento dei campi extra field dei task'
        ],
        'task_mod_autorizzazioni' => [
            'label' => 'Modifica autorizzazioni task',
            'description' => 'Accedi e modifica le autorizzazioni dei task'
        ],
        'task_mod_date' => [
            'label' => 'Modifica date attività',
            'description' => 'Modifica le date preventive delle attività'
        ],
        'task_mod_costi' => [
            'label' => 'Modifica costi task',
            'description' => 'Modifica costi preventivi e consuntivi dei task'
        ],
        'task_mod_stato' => [
            'label' => 'Modifica stati attività',
            'description' => 'Modifica stati delle attività'
        ],
        'task_view_costi' => [
            'label' => 'Visualizza costi task',
            'description' => 'Visualizza qualsiasi costo realtivo al task'
        ],
        'task_view_log' => [
            'label' => 'Visualizza log attvità',
            'description' => 'Visualizza i log ed i cambiamenti di stato delle attività'
        ],
        'task_notify_status' => [
            'label' => 'Assegna notifiche di cambiamento stato attività',
            'description' => 'Abilita alla selezione degli utenti che riceveranno le notifiche di cambiamento di stato delle attività'
        ],
        'task_uploads' => [
            'label' => 'Upload di allegati',
            'description' => 'Abilita al caricamento di allegati nelle attività'
        ],
        'task_print' => [
            'label' => 'Stampa report task',
            'description' => 'Abilita alla stampa del task e dei dettagli'
        ],
        **/
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['el'] = Task::where('id', $request->input('id'))
            ->whereNull('parent_id')
            ->first();
        if (!$data['el']) abort(404);

        $auth = json_decode($data['el']->auth, true);

        $data['controllers'] = $this->controllers;
        foreach ($data['controllers'] as $key => $c) {
            // Log::info($data['el']->id .' -> ' . $key);
            TaskAutorizzazione::firstOrCreate([
                'tasks_root_id' => $data['el']->id,
                'autorizzazione' => $key
            ]);

            $data['usersSel'][$key] = isset($auth[$key]) ? array_flip($auth[$key]) : [];
        }

        $utentiIds = Utente::get()->pluck('id', 'id');
        $data['users'] = User::whereIn('utente_id', $utentiIds)->select('id', 'name as label')->get()->pluck('label', 'id');

        return view('dashboard.tasks.modals.autorizzazioni', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task = Task::where('id', $request->input('root_id'))
            ->whereNull('parent_id')
            ->first();
        if (!$task) abort(404);

        try {

            foreach ($this->controllers as $key => $c) {
                $ids = json_encode($request->input('users_ids.'.$key, []));
                DB::table('tasks_autorizzazioni')
                    ->where('tasks_root_id', $task->id)
                    ->where('autorizzazione', $key)
                    ->update([
                        'users_ids' => $ids
                    ]);
            }

            $auth = $task->autorizzazioni()->get();
            $flatMap = $auth->flatMap(function($item) {
                return [$item->autorizzazione => json_decode($item->users_ids)];
            });

            $task->auth = json_encode($flatMap);
            $task->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);

        }
    }

    public function copy(Request $request)
    {
        $validationRules = [
            'copy_task_id' => 'required',
        ];
        $validatedData = $request->validate($validationRules);

        $task = Task::where('id', $request->input('copy_task_id'))->first();
        if (!$task) {
            abort(404);
        }

        $task = Task::where('id', $request->input('root_id'))
            ->whereNull('parent_id')
            ->first();
        if (!$task)
            abort(404);

        try {

            $authFrom = TaskAutorizzazione::where('tasks_root_id', $task->id)->get();
            foreach ($authFrom as $af) {
                // Log::info('From...' .$af->tasks_root_id. ' copying.. ' . $af->autorizzazione . ' ids.. '. $af->users_ids .' to...' . $task->id);

                $el = TaskAutorizzazione::firstOrCreate([
                    'tasks_root_id' => $task->id,
                    'autorizzazione' => $af->autorizzazione,
                ]);

                $el->users_ids = $af->users_ids;
                $el->save();
            }

            $auth = $task->autorizzazioni()->get();
            $flatMap = $auth->flatMap(function($item) {
                return [$item->autorizzazione => json_decode($item->users_ids)];
            });

            $task->auth = json_encode($flatMap);
            $task->save();

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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
