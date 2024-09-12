<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\CommessaNodeInserted;
use App\Events\Illuminate\Events\CommessaNodeSottoFaseChangedCosti;
use App\Events\Illuminate\Events\TaskAddedUser;
use App\Models\AttachmentCommessa;
use App\Models\AttachmentS3;
use App\Models\Checklist;
use App\Models\Commessa;
use App\Models\CommessaRapportino;
use App\Models\Gruppo;
use App\Models\Scadenza;
use App\Models\Sede;
use App\Models\Task;
use App\Models\TaskTemplate;
use App\Models\User;
use App\Models\Utente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // Log::info($request->all());

        if (!Gate::allows('is-tasks-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Task Manager permette di assegnare task, compiti e attività ad utenti e gruppi di utenti per seguirne l\'andamento e la conclusione e monitorare eventuali ritardi.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if ($request->has('_search')) {
            $query = Task::whereNull('parent_id');

            if ($request->input('clienti_id', null)) {
                $query = $query->where('clienti_id', $request->input('clienti_id'));
            }

            if ($request->input('tags', null)) {
                $tags = explode(',', $request->input('tags'));
                $query = $query->where(function($q) use($tags) {
                    foreach ($tags as $tag) {
                        $q->where('tags', 'LIKE', '%'.$tag.'%');
                    }
                });
            }
            $data['list'] = $query->with("cliente")->orderBy('created_at', 'desc')->paginate(500)->appends(request()->query());
        }
        else {
            $data['list'] = Task::whereNull('parent_id')->with(['cliente'])->paginate(500)->appends(request()->query());
        }

        return view('dashboard.tasks.index', $data);
    }

    public function assegnati(Request $request)
    {
        if (!Gate::allows('is-tasks-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Task Manager permette di assegnare task, compiti e attività ad utenti e gruppi di utenti per seguirne l\'andamento e la conclusione e monitorare eventuali ritardi.';
            return view('layouts.helpers.module-deactive', $data);
        }

        $query = Task::whereNotNull('parent_id')->whereJsonContains('users_ids', [\auth()->user()->id.""]);
        if ($request->has('start_at')) {
            $from = new \Carbon\Carbon($request->input('start_at'));
            $to = new \Carbon\Carbon($request->input('end_at'));
            $query = $query->where(function($q) use($from, $to) {
                $q->whereBetween('data_inizio_prevista', [$from->startOfDay(), $to->endOfDay()])
                ->orWhereNull('data_inizio_prevista');
            });
        }

        $data['list'] = $query->with("root.cliente")->orderBy('created_at', 'desc')->paginate(500)->appends(request()->query());
        return view('dashboard.tasks.assegnati', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Gate::allows('is-tasks-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Task Manager permette di assegnare task, compiti e attività ad utenti e gruppi di utenti per seguirne l\'andamento e la conclusione e monitorare eventuali ritardi.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if (!Gate::allows('can_create_tasks'))
            abort(401);

        $data = [];

        $aziendaId = getAziendaId();
        $data['gruppi'] = Gruppo::whereAziendaId($aziendaId)->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = array_flip(explode(',', ''));


        if ($request->input('_module', null) == 'fast') {
            $utentiIds = Utente::get()->pluck('id', 'id');
            $data['users'] = User::whereIn('utente_id', $utentiIds)->select('id', 'name as label')->get()->pluck('label', 'id');

            return view('dashboard.tasks.create-fast', $data);
        }

        return view('dashboard.tasks.create', $data);
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
        $validationRules = [
            'label' => 'required',
            'tasks_template_id' => 'required'
        ];

        if ($request->input('_module', null) == 'fast') {
            $validationRules['users_ids'] = 'required';
            $validationRules['data_inizio_prevista'] = 'sometimes|nullable|date';
            $validationRules['data_fine_prevista'] = 'sometimes|nullable|date|after:data_inizio_prevista';

            unset($validationRules['tasks_template_id']);
        }

        $validatedData = $request->validate($validationRules);

        DB::beginTransaction();
        try {

            if ($request->input('_module', null) == 'fast') {
                /** Task veloce **/

                $rootN = new Task;

                $rootN->azienda_id = getAziendaId();
                $rootN->clienti_id = $request->input('clienti_id', null);
                $rootN->label = $request->input('label');

                $rootN->tags = $request->input('tags', null);

                $rootId = Str::uuid();
                $rootN->id = $rootId;

                $rootN->saveAsRoot();
                $rootN->id = $rootId;

                $nodeN = new Task();
                $nodeN->azienda_id = $rootN->azienda_id;
                $nodeN->root_id = $rootId;
                $nodeN->label = $request->input('label');
                $nodeN->description = $request->input('description', null);
                $nodeN->data_inizio_prevista = $request->input('data_inizio_prevista', null);
                $nodeN->data_fine_prevista = $request->input('data_fine_prevista', null);

                $ids = json_encode($request->input('users_ids', []));
                $nodeN->users_ids = $ids;

                $nodeId = Str::uuid();
                $nodeN->id = $nodeId;
                $nodeN->appendToNode($rootN)->save();
                $nodeN->id = $nodeId;

                event(new TaskAddedUser($nodeN, []));

            }
            else {
                /** Task standard **/

                $root = TaskTemplate::where('id', $request->input('tasks_template_id'))->whereNull('parent_id')->first();
                if (!$root) abort('404');
                $tree = TaskTemplate::defaultOrder()->descendantsOf($root->id)->toTree();

                $swapIds = [];

                $attributes = ['azienda_id', 'label', 'description'];
                $rootN = new Task;
                foreach ($attributes as $attr) {
                    switch ($attr) {
                        case 'label':
                            $rootN->$attr = $request->input('label');
                            break;
                        default:
                            $rootN->$attr = $root->$attr;
                    }
                }

                $rootN->clienti_id = $request->input('clienti_id', null);
                $rootN->tasks_template_id = $request->input('tasks_template_id');
                $rootN->tags = $request->input('tags', null);


                $rootId = Str::uuid();
                $rootN->id = $rootId;

                $ids = json_encode($request->input('notify_gruppi_ids', []));
                $rootN->notify_gruppi_ids = $ids;

                $rootN->saveAsRoot();
                $rootN->id = $rootId; /** reimpostare per un Bug del nestedset **/
                $swapIds[$root->id] = $rootId;

                foreach ($tree as $node) {
                    $nodeN = new Task();
                    $nodeN->root_id = $rootId;
                    foreach ($attributes as $attr) {
                        $nodeN->$attr = $node->$attr;
                    }

                    $nodeId = Str::uuid();
                    $nodeN->id = $nodeId;
                    $nodeN->appendToNode($rootN)->save();
                    $nodeN->id = $nodeId; /** reimpostare per un Bug del nestedset **/

                    // event(new CommessaNodeInserted($nodeN));

                    $swapIds[$node->id] = $nodeId;

                    /** Disabilito perchè i Task hanno 1 solo livello **/
                    /**
                    if ($node->children) {
                    foreach ($node->children as $child) {
                    $childN = new Commessa();
                    $childN->data_inizio_prevista = $rootN->data_inizio_prevista;
                    $childN->data_fine_prevista = $rootN->data_fine_prevista;
                    $childN->root_id = $rootN->id;
                    $childN->day_to_hours = $rootN->day_to_hours;
                    foreach ($attributes as $attr) {
                    $childN->$attr = $child->$attr;
                    }
                    $childN->appendToNode($nodeN)->save();

                    // event(new CommessaNodeInserted($childN));
                    $swapIds[$child->id] = $childN->id;
                    }
                    }
                     **/
                }
            }




            /** Disabilito perchè non esistono dipendenze ***/
            /**
            $tree = Commessa::descendantsOf($rootN->id)->toFlatTree();
            foreach ($tree as $node) {
                if (isset($swapIds[$node->execute_after_id])) {
                    $node->execute_after_id = $swapIds[$node->execute_after_id];
                    $node->save();
                }
            }

            foreach ($tree as $node) {
                event(new CommessaNodeInserted($node));
            }
            **/

            DB::commit();
            return redirect()->route('task.edit', $rootId);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return redirect()->back()->withInput()->with('error', $payload);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!Gate::allows('is-commesse-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo COMMESSE permette di definire le proprie commesse, organizzarle e pianificarle in base allo stato occupazionale dei propri dipendenti, mezzi, attrezzature e materiali. Gantt e grafici di supporto, allegati e schedulatori permettono di monitorare in tempo reale lo stato di avanzamento della commessa.';
            return view('layouts.helpers.module-deactive', $data);
        }

        $el = Commessa::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;

        if ($request->input('_refresh', null) == 'header') {
            return view('dashboard.commesse.analisi.components.show-header', $data);
        }

        $data['tree'] = Commessa::withDepth()->with('executeAfter')->defaultOrder()->descendantsOf($id)->toTree();
        if ($request->input('_refresh', null) == 'overview-table') {
            return view('dashboard.commesse.analisi.overview', $data);
        }

        $data['gruppi'] = Gruppo::whereAziendaId($el->azienda_id)->orderBy('label')->get()->pluck('label', 'id');
        $data['sedi'] = Sede::whereAziendaId($el->azienda_id)->orderBy('label')->get()->pluck('label', 'id');
        $data['utenti'] = Utente::with('user')->orderBy('extras1')->get();
        $data['utenti'] = $data['utenti']->filter(function ($value, $key) {
            return $value->user->active == '1';
        })->mapWithKeys(function ($item) {
            return [$item->id => $item->extras1.' '.$item->extras2];
        });

        return view('dashboard.commesse.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('is-tasks-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Task Manager permette di assegnare task, compiti e attività ad utenti e gruppi di utenti per seguirne l\'andamento e la conclusione e monitorare eventuali ritardi.';
            return view('layouts.helpers.module-deactive', $data);
        }

        $el = Task::find($id);
        if (!$el) abort('404');

        if (!Gate::allows('can_create_tasks'))
            abort(401);

        $data['gruppi'] = Gruppo::whereAziendaId($el->azienda_id)->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = $el->notify_gruppi_ids ? array_flip(json_decode($el->notify_gruppi_ids)) : [];

        // dd($data['gruppiSel']);

        $data['el'] = $el;
        $data['tree'] = Task::defaultOrder()->with('root')->descendantsOf($id)->toTree();

        // dd($data['tree']);

        $utentiIds = Utente::get()->pluck('id', 'id');
        $data['users'] = User::whereIn('utente_id', $utentiIds)->select('id', 'name as label')->get()->pluck('label', 'id');
        // $data['usersNotificationSel'] = $el->notification_users_ids ? array_flip(json_decode($el->notification_users_ids)) : [];

        // Allegati
        $ids = Task::where('id', $id)->orWhere('root_id', $id)->get()->pluck('id');
        $data['listAttachments'] = AttachmentS3::whereIn('reference_id', $ids)
            ->where('reference_table', 'tasks')
            ->where('to_delete', '0')
            ->with('node')
            ->get();

        return view('dashboard.tasks.create', $data);
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
        // Log::info($request->all());

        $el = Task::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module')) {
            case 'map':
            case 'extra-field':
                $validationRules = [];
                break;
            default:
                $validationRules = [
                    'label' => 'required',
                ];
        }

        $validatedData = $request->validate($validationRules);
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                default:
                    $el->$k = $v;
            }
        }

        try {

            $ids = json_encode($request->input('notify_gruppi_ids', []));
            $el->notify_gruppi_ids = $ids;

            $el->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }catch (\Exception $e) {

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
        //
    }

    public function refreshTree($id) {
        $el = Task::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $data['tree'] = Task::defaultOrder()->descendantsOf($id)->toTree();

        return view('dashboard.tasks.components.tree', $data);
    }

    public function allegati($id) {
        $el = Task::find($id);
        if (!$el) abort(404);

        /** Allegati Task **/
        $ids = Task::where('id', $id)->orWhere('root_id', $id)->get()->pluck('id');
        $data['listAttachmentsTask'] = AttachmentS3::whereIn('reference_id', $ids)
            ->where('reference_table', 'tasks')
            ->where('to_delete', '0')
            ->with('node')
            ->get();

        /** Allegati rapportini Task **/
//        $ids = CommessaRapportino::where('commesse_root_id', $id)->get()->pluck('id');
//        $data['listAttachmentsCommessaRapportini'] = AttachmentS3::whereIn('reference_id', $ids)
//            ->where('reference_table', 'commesse_rapportini')
//            ->where('to_delete', '0')
//            ->with('rapportino')
//            ->get();

        $data['commessa'] = $el;
        return view('dashboard.tasks.analisi.allegati', $data);
    }

    public function avvisi($id) {
        $el = Task::find($id);
        if (!$el) abort(404);

        /** Avvisi commessa **/
        $data['listAvvisiTask'] = Scadenza::where('tasks_id', $id)
            ->get();

        return view('dashboard.tasks.analisi.avvisi', $data);
    }

    public function print(Request $request, $id) {

        if (!Gate::allows('is-commesse-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo COMMESSE permette di definire le proprie commesse, organizzarle e pianificarle in base allo stato occupazionale dei propri dipendenti, mezzi, attrezzature e materiali. Gantt e grafici di supporto, allegati e schedulatori permettono di monitorare in tempo reale lo stato di avanzamento della commessa.';
            return view('layouts.helpers.module-deactive', $data);
        }

        // dd($request->all());
        $data['commessa'] = Commessa::with('azienda')->find($id);
        if (!$data['commessa']) abort(404);

        $ids = Commessa::where('root_id', $id)->orWhere('id', $id)->pluck('id', 'id');

        $data['azienda'] = $data['commessa']->azienda;
        $data['tree'] = Commessa::defaultOrder()->descendantsOf($id)->toTree();
        $data['flatTree'] = Commessa::with(['descendants' => function($query) {
            $query->orderBy('type');
        }])->defaultOrder()->descendantsOf($id)->toFlatTree();

        $data['nodeWithRisorse'] = $data['flatTree']->filter(function($node) {
            if ($node->descendants->count()) {
                return $node->descendants->first()->item_id != null;
            }
           return false;
        });

        $data['totaleFasi'] = $data['flatTree']->reduce(function($qti, $node) {
            if ($node->type == 'fase_lv_1') {
                return $qti + 1;
            }
            return $qti;
        }, 0);

        $data['totaleSottoFasi'] = $data['flatTree']->reduce(function($qti, $node) {
            if ($node->type == 'fase_lv_2') {
                return $qti + 1;
            }
            return $qti;
        }, 0);

        $data['logs'] = Commessa::where('root_id', $id)
            ->whereNotNull('item_id')
            ->orderBy('type')
            ->with(['logs', 'logs.commessa.parent'])
            ->get()
            ->groupBy('item_id');

        $data['rapportini'] = CommessaRapportino::where('commesse_root_id', $id)
            ->orderBy('commesse_id')
            ->orderBy('start')
            ->with('commessa')
            ->get();


        $data['checklist'] = Checklist::whereIn('reference_id', $ids)
            ->orderBy('reference_id')
            ->with('tpl', 'data', 'node')
            ->get();

        // dd($data['logs']);

        $ids = Commessa::where('id', $id)->orWhere('root_id', $id)->get()->pluck('id');
        $data['allegati'] = AttachmentCommessa::whereIn('commesse_id', $ids)
            ->orderBy('commesse_id')
            ->with('node')
            ->get();

        $pdf = PDF::loadView('pdf.commessa.index', $data);

        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('commessa-'.time().'.pdf');
    }

    public function select2(Request $request)
    {
        $t = $request->input('term', null);
        $list = [];
        if (trim($t) != '') {
            $list = Task::where('label', 'like', '%'.$t.'%')
                ->whereNull('type')
                ->orderBy('label')
                ->get();
        }
        else {
            $list = Task::whereNull('root_id')
                ->orderBy('created_at', 'desc')
                ->orderBy('label')
                ->limit(10)
                ->get();
        }

        if ($list->count()) {
            $list = $list->map(function ($item){
                return ['id' => $item->id, 'text' => $item->label];
            });
        }

        $data['results'] = $list;
        return response()->json($data);
    }

}
