<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ChecklistTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChecklistTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('is-checklist-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo CHECKLIST permette di creare modelli di raccolta dati personalizzati e di associarli a moduli di Infosituata per un completamento di informazioni aziendali.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(Gate::denies('can_create_template_checklist'))
            abort(401);

        $data['list'] = ChecklistTemplate::whereNull('root_id')->paginate(500)->appends(request()->query());
        return view('dashboard.checklist-tpl.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('is-checklist-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo CHECKLIST permette di creare modelli di raccolta dati personalizzati e di associarli a moduli di Infosituata per un completamento di informazioni aziendali.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(Gate::denies('can_create_template_checklist'))
            abort(401);

        $data = [];
        return view('dashboard.checklist-tpl.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
            'label' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new ChecklistTemplate();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        try {
            $el->azienda_id = getAziendaId();
            $el->key = Str::uuid();
            $el->saveAsRoot();

            return redirect()->route('checklist-template.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
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
        if (!Gate::allows('is-checklist-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo CHECKLIST permette di creare modelli di raccolta dati personalizzati e di associarli a moduli di Infosituata per un completamento di informazioni aziendali.';
            return view('layouts.helpers.module-deactive', $data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('is-checklist-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo CHECKLIST permette di creare modelli di raccolta dati personalizzati e di associarli a moduli di Infosituata per un completamento di informazioni aziendali.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(Gate::denies('can_create_template_checklist'))
            abort(401);

        $el = ChecklistTemplate::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $data['modulesEnabled'] = $el->modules_enabled ? array_flip(json_decode($el->modules_enabled)) : null;
        $data['tree'] = ChecklistTemplate::defaultOrder()->descendantsOf($id)->toTree();

        return view('dashboard.checklist-tpl.create', $data);
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

        $el = ChecklistTemplate::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module')) {
            default:
                $validationRules = [
                    'label' => 'required',
                ];
        }
        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'extra', 'modules_enabled']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        try {

            $el->modules_enabled = $request->input('modules_enabled') ? json_encode($request->input('modules_enabled')) : null;
            $el->save();

//            $payload = 'Salvataggio avvenuto correttamente!';
//            if ($request->get('_type') == 'json')
//                return response()->json(['res' => 'success','payload' => $payload]);

            return redirect()->back()->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
//            if ($request->get('_type') == 'json')
//                return response()->json(['res' => 'error', 'payload' => $payload]);

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

        $el = ChecklistTemplate::find($id);
        if (!$el) abort(404);

        try {
            $el->delete();

            return redirect()->to($request->get('_redirect'))->with('success', 'A breve il sistema cancellerà l\'elemento!');
        }catch (\Exception $e) {

            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

    public function refreshTree($id) {
        $el = ChecklistTemplate::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $data['tree'] = ChecklistTemplate::defaultOrder()->descendantsOf($id)->toTree();

        return view('dashboard.checklist-tpl.components.tree', $data);
    }

    public function duplicate($id) {
        $root = ChecklistTemplate::where('id', $id)->whereNull('root_id')->first();
        if (!$root) abort('404');

        $tree = ChecklistTemplate::defaultOrder()->descendantsOf($id)->toTree();

        DB::beginTransaction();
        try {

            $swapIds = [];

            $attributes = ['azienda_id', 'key', 'label', 'description', 'type', 'value', 'required', 'modules_enabled', 'root_id'];
            $rootN = new ChecklistTemplate();
            foreach ($attributes as $attr) {
                switch ($attr) {
                    case 'label':
                        $rootN->$attr = $root->$attr . ' (Copia)';
                        break;
                    case 'key':
                        $rootN->$attr = Str::uuid();
                        break;
                    default:
                        $rootN->$attr = $root->$attr;
                }
            }
            $rootN->saveAsRoot();

            foreach ($tree as $node) {
                $nodeN = new ChecklistTemplate;
                foreach ($attributes as $attr) {
                    switch ($attr) {
                        case 'key':
                            $nodeN->$attr = Str::uuid();
                            break;
                        case 'root_id':
                            $nodeN->$attr = $rootN->id;
                            break;
                        default:
                            $nodeN->$attr = $node->$attr;
                    }
                }
                $nodeN->appendToNode($rootN)->save();
                $swapIds[$node->id] = $nodeN->id;

                if ($node->children) {
                    foreach ($node->children as $child) {
                        $childN = new ChecklistTemplate();
                        foreach ($attributes as $attr) {
                            switch ($attr) {
                                case 'key':
                                    $childN->$attr = Str::uuid();
                                    break;
                                case 'root_id':
                                    $childN->$attr = $rootN->id;
                                    break;
                                default:
                                    $childN->$attr = $child->$attr;
                            }
                        }
                        $childN->appendToNode($nodeN)->save();
                        $swapIds[$child->id] = $childN->id;
                    }
                }
            }

            DB::commit();

            return redirect()->route('checklist-template.edit', $rootN->id);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return redirect()->back()->withInput()->with('error', $payload);
        }
    }

}
