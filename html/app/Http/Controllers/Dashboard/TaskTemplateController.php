<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\TaskTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TaskTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!Gate::allows('is-tasks-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Task Manager permette di assegnare task, compiti e attività ad utenti e gruppi di utenti per seguirne l\'andamento e la conclusione e monitorare eventuali ritardi.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(Gate::denies('can_create_tasks_template'))
            abort(401);

        $data['list'] = TaskTemplate::whereNull('parent_id')->paginate(500)->appends(request()->query());
        return view('dashboard.tasks-tpl.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('is-tasks-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Task Manager permette di assegnare task, compiti e attività ad utenti e gruppi di utenti per seguirne l\'andamento e la conclusione e monitorare eventuali ritardi.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(Gate::denies('can_create_tasks_template'))
            abort(401);

        $data = [];
        return view('dashboard.tasks-tpl.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
            'label' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new TaskTemplate;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {

            $uuid = Str::uuid();
            $el->id = $uuid;
            $el->azienda_id = getAziendaId();
            $el->saveAsRoot();
            DB::commit();

            return redirect()->route('task-template.edit', $uuid)->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
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
        if(Gate::denies('can_create_tasks_template'))
            abort(401);

        $el = TaskTemplate::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $data['tree'] = TaskTemplate::defaultOrder()->descendantsOf($id)->toTree();
        return view('dashboard.tasks-tpl.create', $data);
    }

    public function refreshTree($id) {
        $el = TaskTemplate::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $data['tree'] = TaskTemplate::defaultOrder()->descendantsOf($id)->toTree();

        return view('dashboard.tasks-tpl.components.tree', $data);
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
        $el = TaskTemplate::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module')) {
            case 'extra-field':
                $validationRules = [];
                break;
            default:
                $validationRules = [
                    'label' => 'required',
                ];
        }
        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'extra']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {

            if ($request->has('extra')) {
                $el->extra_fields = json_encode($request->input('extra'));
            }

            $el->save();
            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);

            return redirect()->back()->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

            return redirect()->back()->withInput()->with('error', $payload);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(!$request->has('confirm'))
            return redirect()->back()->withInput()->with('error', 'E\' necessario confermare la cancellazione!');

        $el = TaskTemplate::find($id);
        if (!$el) abort(404);

        try {
            $el->delete();

            return redirect()->to($request->get('_redirect'))->with('success', 'A breve il sistema cancellerà l\'elemento!');
        }catch (\Exception $e) {

            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

    public function duplicate($id) {
        $root = TaskTemplate::where('id', $id)->whereNull('parent_id')->first();
        if (!$root) abort('404');

        $tree = TaskTemplate::defaultOrder()->descendantsOf($id)->toTree();

        DB::beginTransaction();
        try {

            $swapIds = [];

            $attributes = ['azienda_id', 'label', 'description'];
            $rootN = new TaskTemplate();
            foreach ($attributes as $attr) {
                switch ($attr) {
                    case 'label':
                        $rootN->$attr = $root->$attr . ' (Copia)';
                        break;
                    default:
                        $rootN->$attr = $root->$attr;
                }
            }
            $rootId = Str::uuid();
            $rootN->id = $rootId;
            $rootN->saveAsRoot();

            $rootN->id = $rootId; /** reimpostare per un Bug del nestedset **/

            $swapIds[$root->id] = $rootN->id;

            foreach ($tree as $node) {
                $nodeN = new TaskTemplate();
                foreach ($attributes as $attr) {
                    $nodeN->$attr = $node->$attr;
                }

                $nodeId = Str::uuid();
                $nodeN->id = $nodeId;
                $nodeN->appendToNode($rootN)->save();
                $nodeN->id = $nodeId; /** reimpostare per un Bug del nestedset **/

                $swapIds[$node->id] = $nodeId;

                if ($node->children) {
                    foreach ($node->children as $child) {
                        $childN = new TaskTemplate();
                        foreach ($attributes as $attr) {
                            $childN->$attr = $child->$attr;
                        }
                        $childId = Str::uuid();
                        $childN->id = $childId;
                        $childN->appendToNode($nodeN)->save();
                        $swapIds[$child->id] = $childId;
                    }
                }
            }

            /**** Non c'è il concetto di Execute After
            $tree = TaskTemplate::descendantsOf($rootN->id)->toFlatTree();
            foreach ($tree as $node) {
                if (isset($swapIds[$node->execute_after_id])) {
                    $node->execute_after_id = $swapIds[$node->execute_after_id];
                    $node->save();
                }
            }
            ****/

            DB::commit();

            return redirect()->route('task-template.edit', $rootId);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return redirect()->back()->withInput()->with('error', $payload);
        }
    }

    public function select2(Request $request)
    {
        $t = $request->input('term', null);
        $list = [];
        if (trim($t) != '') {
            $list = TaskTemplate::where('label', 'like', '%'.$t.'%')
                ->whereNull('parent_id')
                ->orderBy('label')
                ->get();
        }
        else {
            $list = TaskTemplate::whereNull('parent_id')
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
