<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\CommessaTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CommessaTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!Gate::allows('is-commesse-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo COMMESSE permette di definire le proprie commesse, organizzarle e pianificarle in base allo stato occupazionale dei propri dipendenti, mezzi, attrezzature e materiali. Gantt e grafici di supporto, allegati e schedulatori permettono di monitorare in tempo reale lo stato di avanzamento della commessa.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(Gate::denies('can_create_commesse_template'))
            abort(401);

        $data['list'] = CommessaTemplate::whereNull('type')->paginate(500)->appends(request()->query());
        return view('dashboard.commesse-tpl.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('is-commesse-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo COMMESSE permette di definire le proprie commesse, organizzarle e pianificarle in base allo stato occupazionale dei propri dipendenti, mezzi, attrezzature e materiali. Gantt e grafici di supporto, allegati e schedulatori permettono di monitorare in tempo reale lo stato di avanzamento della commessa.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(Gate::denies('can_create_commesse_template'))
            abort(401);

        $data = [];
        return view('dashboard.commesse-tpl.create', $data);
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

        $el = new CommessaTemplate();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {
            $el->azienda_id = getAziendaId();
            $el->saveAsRoot();
            DB::commit();

            return redirect()->action('Dashboard\CommessaTemplateController@edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
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
        if(Gate::denies('can_create_commesse_template'))
            abort(401);

        $el = CommessaTemplate::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $data['tree'] = CommessaTemplate::with('executeAfter')->defaultOrder()->descendantsOf($id)->toTree();
        return view('dashboard.commesse-tpl.create', $data);
    }

    public function refreshTree($id) {
        $el = CommessaTemplate::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $data['tree'] = CommessaTemplate::defaultOrder()->descendantsOf($id)->toTree();

        return view('dashboard.commesse-tpl.components.tree', $data);
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
        $el = CommessaTemplate::find($id);
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

        $el = CommessaTemplate::find($id);
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
        $root = CommessaTemplate::where('id', $id)->whereNull('type')->first();
        if (!$root) abort('404');

        $tree = CommessaTemplate::defaultOrder()->descendantsOf($id)->toTree();

        DB::beginTransaction();
        try {

            $swapIds = [];

            $attributes = ['azienda_id', 'item_id', 'label', 'item_label', 'type', 'execute_after_id', 'time', 'color'];
            $rootN = new CommessaTemplate();
            foreach ($attributes as $attr) {
                switch ($attr) {
                    case 'label':
                        $rootN->$attr = $root->$attr . ' (Copia)';
                        break;
                    default:
                        $rootN->$attr = $root->$attr;
                }
            }
            $rootN->saveAsRoot();
            $swapIds[$root->id] = $rootN->id;

            foreach ($tree as $node) {
                $nodeN = new CommessaTemplate();
                foreach ($attributes as $attr) {
                    $nodeN->$attr = $node->$attr;
                }
                $nodeN->appendToNode($rootN)->save();
                $swapIds[$node->id] = $nodeN->id;

                if ($node->children) {
                    foreach ($node->children as $child) {
                        $childN = new CommessaTemplate();
                        foreach ($attributes as $attr) {
                            $childN->$attr = $child->$attr;
                        }
                        $childN->appendToNode($nodeN)->save();
                        $swapIds[$child->id] = $childN->id;
                    }
                }
            }

            $tree = CommessaTemplate::descendantsOf($rootN->id)->toFlatTree();
            foreach ($tree as $node) {
                if (isset($swapIds[$node->execute_after_id])) {
                    $node->execute_after_id = $swapIds[$node->execute_after_id];
                    $node->save();
                }
            }

            DB::commit();

            return redirect()->action('Dashboard\CommessaTemplateController@edit', $rootN->id);
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
            $list = CommessaTemplate::where('label', 'like', '%'.$t.'%')
                ->whereNull('type')
                ->orderBy('label')
                ->get();
        }
        else {
            $list = CommessaTemplate::whereNull('type')
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
