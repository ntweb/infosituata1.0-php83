<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\AttachmentS3ParentDeleted;
use App\Models\Checklist;
use App\Models\ChecklistData;
use App\Models\ChecklistTemplate;
use App\Models\Commessa;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use PDF;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // dd($request->all());
        switch ($request->input('reference_controller')) {
            case 'commesse':
                $ids = Commessa::where('root_id', $request->input('reference_id'))
                    ->whereIn('type', ['fase_lv_1', 'fase_lv_2'])
                    ->get()
                    ->pluck('id');
                // dd($ids);

                $list = Checklist::whereIn('reference_id', $ids)
                    ->where('reference_controller', $request->input('reference_controller'))
                    ->orderBy('id')
                    ->get();

                $data['list'] = $list;
                // dd($list);

                return view('dashboard.commesse.analisi.checklist', $data);

            default:
                if (!Gate::allows('is-checklist-module-enabled')) {
                    $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
                    $data['message_2'] = 'Il modulo CHECKLIST permette di creare modelli di raccolta dati personalizzati e di associarli a moduli di Infosituata per un completamento di informazioni aziendali.';
                    return view('layouts.helpers.module-deactive', $data);
                }
        }

        $query = Checklist::where('reference_controller', '!=', 'commesse')
            ->orderBy('id', 'desc')
            ->with(['tpl', 'item', 'user']);

        if ($request->has('_search')) {
            $data['list'] = [];

            if ($request->input('search')) {
                $search  = collect($request->input('search'))->filter(function($value, $key) {
                    return isset($value);
                })->toArray();


                if (count($search)) {

                    foreach ($search as $k => $v) {
                        switch ($k) {
                            case 'start_at':
                            case 'end_at':
                                $st = new \Carbon\Carbon($search['start_at']);
                                $en = new \Carbon\Carbon($search['end_at']);
                                $query = $query->whereBetween('created_at', [$st->startOfDay(), $en->endOfDay()]);
                                break;
                            default:
                                $query = $query->where($k, $v);
                        }
                    }

                    $data['list'] = $query->paginate(500);
                }
            }

            return view('dashboard.checklist.tables.index', $data);
        }

        $data['list'] = $query->paginate(500);
        return view('dashboard.checklist.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Gate::allows('is-checklist-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo CHECKLIST permette di creare modelli di raccolta dati personalizzati e di associarli a moduli di Infosituata per un completamento di informazioni aziendali.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if ($request->has('reference_controller')) {
            $controller = $request->input('reference_controller');

            $checklists = ChecklistTemplate::whereNull('root_id')
                ->where('active', '1')
                ->where('fl_prod', '1')
                ->whereJsonContains('modules_enabled', $controller)
                ->orderBy('label')
                ->get()
                ->pluck('label', 'id');

            $data['items_id'] =$request->input('items_id', null);
            $data['reference_controller'] = $controller;
            $data['checklists'] = $checklists;
            return view('dashboard.checklist.create-select-template', $data);
        }


        $checklists_controllers = collect(ModuloChecklistAutorizzazioniController::getControllers())
            ->reduce(function($c, $item) {

                // Log::info($item['key']);
                if (Gate::allows('can-create-checklist', 'can_create_checklist_'.$item['key']))
                    $c[$item['key']] = $item['label'];

                return $c;
            }, []);

        $data['checklists_controllers'] = array_merge(['' => '-'], $checklists_controllers);

        return view('dashboard.checklist.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tpl = ChecklistTemplate::find($request->input('checklists_templates_id'));
        if (!$tpl) abort(404);

        $tree = ChecklistTemplate::descendantsOf($tpl->id)->toFlatTree();
        $tree = $tree->filter(function($item) {
            if (!$item->type) return false;
            if ($item->type == 'sezione') return false;

            return true;
        });

        // Log::info($tree);
        $validationRules = [];
        foreach ($tree as $inp) {
            if ($inp->required)
                $validationRules[$inp->key] = 'required';
        }

        // Log::info($validationRules);
        // Log::info($request->all());

        $validatedData = $request->validate($validationRules);

        DB::beginTransaction();
        try {
            if ($request->has('id')) {
                $checklist = Checklist::find($request->input('id'));

                if (!Gate::allows('can-update-checklist', $checklist)) {
                    throw new \Exception('Non si hanno i diritti per l\'aggiornamento della checklist');
                }
            }
            else {
                $checklist = new Checklist;
                $checklist->azienda_id = getAziendaId();
                $checklist->checklists_templates_id = $tpl->id;
                $checklist->reference_id = $request->input('reference_id', 0);
                $checklist->reference_controller = $request->input('reference_controller', 'checklist-generica');
                $checklist->users_id = auth()->user()->id;
                $checklist->username = auth()->user()->name;
                $checklist->save();
            }

            foreach ($tree as $inp) {
                $chkData = ChecklistData::firstOrNew(['checklists_id' => $checklist->id, 'key' => $inp->key]);

                switch ($inp->type) {
                    case 'textarea':
                        $chkData->value_big = $request->input($inp->key, null);
                        break;

                    case 'checkbox':
                        $chkData->value = json_encode($request->input($inp->key, null));
                        break;

                    default:
                        $chkData->value = $request->input($inp->key, null);
                }

                $chkData->save();
            }

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';

            if ($request->has('reopenForm')) {
                $payload = action('Dashboard\ChecklistController@show', $checklist->id);
                return response()->json($payload);
            }
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio: ' . $e->getMessage();
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
        $checklist = Checklist::find($id);
        if (!$checklist) abort(404);

        $tpl = ChecklistTemplate::defaultOrder()->descendantsAndSelf($checklist->checklists_templates_id)->toTree();
        if (!$tpl) abort(404);

        $data['id'] = $id;
        $data['checklist'] = $tpl->first();
        $data['action'] = '#';

        /** Commesse **/
        if ($checklist->reference_controller == 'commesse') {
            $node = Commessa::find($checklist->reference_id);
            if (!$node) abort(404);

            $data['reference_id'] = $node->id;
            $data['reference_controller'] = 'commesse';

            $data['checklistData'] = ChecklistData::where('checklists_id', $checklist->id)->get()->groupBy('key')->toArray();

            $data['action'] = action('Dashboard\ChecklistController@store');
            $data['callback'] = 'refreshChecklist();';
        }

        /** Items o generica **/
        if ($checklist->reference_controller != 'commesse') {

            if ($checklist->reference_id > 0) {
                $item = Item::find($checklist->reference_id);
                if (!$item) abort(404);
            }

            $data['checklistData'] = ChecklistData::where('checklists_id', $checklist->id)->get()->groupBy('key')->toArray();


            $data['checklistId'] = $checklist->id;
            $data['label'] = isset($item) ? $item->label : 'Generica';
            $data['reference_id'] = isset($item) ? $item->id : 0;
            $data['reference_controller'] = $checklist->reference_controller;
            $data['action'] = action('Dashboard\ChecklistController@store');
            $data['callback'] = 'closeAllModal();';

        }

        return view('dashboard.checklist.modals.render', $data);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $el = Checklist::find($id);
        if (!$el) abort(404);

        try {
            $el->delete();

            /** Deleting s3 attachments **/
            event(new AttachmentS3ParentDeleted($id, 'checklists'));

            $payload = 'Cancellazione avvenuta correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }catch (\Exception $e) {
            Log::info($e->getMessage());
            $payload = 'Errore in fase di cancellazione!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function render(Request $request, $id_template) {

        if ($request->has('reference_controller')) {
            /** faccio un controllo preliminare per vedere se il template è realmente associabile **/
            $tpl = ChecklistTemplate::where('id', $id_template)
                ->whereJsonContains('modules_enabled', $request->input('reference_controller'))
                ->first();
            if (!$tpl) abort(404);
        }

        $tpl = ChecklistTemplate::defaultOrder()->descendantsAndSelf($id_template)->toTree();
        if (!$tpl) abort(404);

        $data['checklist'] = $tpl->first();
        // $data['action'] = '#';
        $data['action'] = action('Dashboard\ChecklistController@store');

        /** Commesse **/
        if ($request->has('node')) {
            $node = Commessa::find($request->input('node'));
            if (!$node) abort(404);

            $data['reference_id'] = $node->id;
            $data['reference_controller'] = 'commesse';

            // $data['action'] = action('Dashboard\ChecklistController@store');
            $data['callback'] = 'refreshChecklist();';
        }

        /** Items o generica **/
        if ($request->has('items_id')) {
            $item = Item::find($request->input('items_id'));
            if (!$item) abort(404);

            $data['label'] = $item->label;
            $data['reference_id'] = $item->id;
            $data['reference_controller'] = $request->input('reference_controller');
            // $data['action'] = action('Dashboard\ChecklistController@store');
            // $data['callback'] = 'window.location.replace("'. route('checklist.index') .'");';
            $data['callback'] = 'reopenLastSavedChecklist';
            $data['formClass'] = 'ns-payload';
            $data['reopenForm'] = 1;
        }

        return view('dashboard.checklist.modals.render', $data);
    }

    public function commessa($id_commessa) {

        $data['el'] = Commessa::find($id_commessa);
        if (!$data['el']) abort(404);

        $data['checklists'] = ChecklistTemplate::whereNull('root_id')
                ->where('active', '1')
                ->where('fl_prod', '1')
                ->whereJsonContains('modules_enabled', 'commesse')
                ->get()
                ->pluck('label', 'id');

        $data['action'] = '#';
        return view('dashboard.checklist.modals.commessa', $data);
    }

    public function print(Request $request, $id) {

        // dd($request->all());
        $data['r'] = Checklist::with('azienda', 'tpl', 'data')->find($id);
        $tpl = ChecklistTemplate::defaultOrder()->descendantsAndSelf($data['r']->checklists_templates_id)->toTree();
        $data['tpl'] = $tpl->first();
        // dd($data['tpl']);


        $data['azienda'] = $data['r']->azienda;

        $pdf = PDF::loadView('pdf.checklist.index', $data);

        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('commessa-'.time().'.pdf');
    }
}
