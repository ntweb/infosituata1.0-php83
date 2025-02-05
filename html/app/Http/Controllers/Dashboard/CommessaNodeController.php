<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\AttachmentS3ParentDeleted;
use App\Events\Illuminate\Events\CommessaNodeChangedStatus;
use App\Events\Illuminate\Events\CommessaNodeDeleted;
use App\Events\Illuminate\Events\CommessaNodeInserted;
use App\Events\Illuminate\Events\CommessaNodeSottoFaseChangedCosti;
use App\Events\Illuminate\Events\CommessaNodeSottoFaseChangedDateEffettive;
use App\Events\Illuminate\Events\CommessaNodeSottoFaseChangedDatePreviste;
use App\Events\Illuminate\Events\CommessaRicalculateCosts;
use App\Exceptions\CommessaNodeException;
use App\Listeners\DeleteScadenzaCommessa;
use App\Models\Commessa;
use App\Models\CommessaLog;
use App\Models\Gruppo;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CommessaNodeController extends Controller
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

        $parent = Commessa::find($request->input('node'));
        if (!$parent) abort('404');

        $data['parent'] = $parent;

        if (!$request->has('_module')) {
            if (!$parent->fl_can_have_sottofase) {
                $data['error'] = 'La fase selezionata: ' . $parent->label . ', è già di tipo operativo poichè contiene utenti / mezzi / attrezzature / materiali';
                $data['error'] .= ' Non è possibile creare sottofasi';

                return view('dashboard.commesse.modals.create-node-assignment-error', $data);
            }
        }
        else {
            if (!$parent->fl_can_have_item) {
                $data['error'] = 'La fase selezionata: ' . $parent->label . ', contiene sottofasi operative';
                $data['error'] .= ' Non è possibile associare direttamente utenti / mezzi / attrezzature / materiali';

                return view('dashboard.commesse.modals.create-node-assignment-error', $data);
            }
        }

        if ($request->has('_module')) {
            switch ($request->input('_module')) {
                case 'mezzo':
                    $title = 'mezzo';

                    break;
                case 'attrezzatura':
                    $title = 'attrezzo';

                    break;
                case 'materiale':
                    $title = 'materiale';

                    break;
                case 'squadra':
                    $title = 'squadra';

                    break;
                default:
                    $data['gruppi'] = Gruppo::orderBy('label')->get();
                    $title = 'utente';
            }

            $data['title'] = 'Assegnazione ' .$title;
            $data['sub_title'] = 'Assegna a: ' . strtolower($parent->label);
            $data['action'] = route('commessa-node.store', ['_parent_id' => $parent->id]);
            $data['search_route'] = route('item.search');

            if ($request->input('_module', 'null') == 'squadra') {
                $data['action'] = route('commessa-node.squadra', [$parent->id, 'xxx']);
                $data['search_route'] = route('squadra.search');
            }

            return view('dashboard.commesse.modals.create-node-assignment', $data);
        }

        $data['title'] = 'Crea elemento';
        $data['sub_title'] = 'Sotto elemento di: ' . strtolower($parent->label);
        $data['action'] = route('commessa-node.store', ['_parent_id' => $parent->id]);
        return view('dashboard.commesse.modals.create-node', $data);
    }

    public function newItem(Request $request, $node)
    {
        $parent = Commessa::find($node);
        if (!$parent) abort('404');

        $data['parent'] = $parent;

        if ($request->has('_module')) {
            switch ($request->input('_module')) {
                case 'mezzo':
                    $title = 'mezzo';
                    $data['action'] = route('mezzi.store');
                    $data['item_store_view'] = 'dashboard.mezzi.forms.parts.create-fields';
                    break;
                case 'attrezzatura':
                    $title = 'attrezzo';
                    $data['action'] = route('attrezzature.store');
                    $data['item_store_view'] = 'dashboard.attrezzature.forms.parts.create-fields';
                    break;
                case 'materiale':
                    $title = 'materiale';
                    $data['action'] = route('materiali.store');
                    $data['item_store_view'] = 'dashboard.materiali.forms.parts.create-fields';
                    break;
                default:
                    $title = 'utente';
                    $data['action'] = route('user.store');
                    $data['item_store_view'] = 'dashboard.user.forms.parts.create-fields';
            }

            $data['title'] = 'Crea nuovo ' .$title;
            return view('dashboard.commesse.modals.create-item', $data);
        }
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

        $parent = Commessa::with('root')->find($request->input('_parent_id'));
        if (!$parent) abort('404');


        $el = new Commessa;
        $el->root_id = $parent->root_id ?? $parent->id;
        $el->day_to_hours = $parent->day_to_hours;
        $neighbor = null;
        switch ($request->input('_module', null)) {
            case 'utente':
            case 'mezzo':
            case 'attrezzatura':
            case 'materiale':
                $item = Item::where('id', $request->input('commessa_item_id'))->first();
                if (!$item) abort('404');

                /** controllo esistenza assegnazione **/
                $exist = Commessa::where('item_id', $request->input('commessa_item_id'))
                    ->where('parent_id', $parent->id)
                    ->count();

                if ($exist) {
                    $payload = 'Errore, elemento già assegnato!';
                    return response()->json(['res' => 'error', 'payload' => $payload]);
                }

                /** cerco un nodo simile se esiste **/
                $neighbor = $exist = Commessa::where('parent_id', $parent->id)
                    ->where('type', $item->controller)
                    ->orderBy('created_at', 'desc')
                    ->first();

                /** cerco se per la stessa tipologia è impostato qualche prezzo **/
                $same = Commessa::where('item_id', $item->id)->where(function($query) {
                    $query->where('costo_item_giornaliero_previsto', '>', 0)
                        ->orWhere('costo_item_orario_previsto', '>', 0)
                        ->orWhere('costo_previsto', '>', 0);
                })->orderBy('id', 'desc')->first();

                if ($same) {
                    $el->costo_item_giornaliero_previsto = $same->costo_item_giornaliero_previsto;
                    $el->costo_item_orario_previsto = $same->costo_item_orario_previsto;
                    $el->costo_previsto = $same->costo_previsto;
                }

                break;

            default:
                $validationRules = ['label' => 'required'];
                $validatedData = $request->validate($validationRules);

                $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
                foreach ($fields as $k => $v) {
                    $el->$k = $v;
                }
        }

        try {
            $el->azienda_id = $parent->azienda_id;

            $el->type = $parent->isRoot() ? 'fase_lv_1' : 'fase_lv_2';
            $el->time = 'h';

            if (isset($item)) {
                $el->item_id = $item->id;
                $el->label = $item->label;
                $el->type = $item->controller;
            }

            $el->color = random_color();

            if ($neighbor) {
                $el->afterNode($neighbor)->save();
            }
            else {
                $el->appendToNode($parent)->save();
            }

            event(new CommessaNodeInserted($el));

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
        $data['el'] = Commessa::find($id);
        if (!$data['el']) abort('404');

        if ($request->has('delete')) {
            $data['title'] = 'Eliminazione: ' . $data['el']->label;
            $data['action'] = route('commessa-node.destroy', $id);
            return view('dashboard.commesse.modals.delete-node', $data);
        }


        $data['siblings'] = $data['el']->siblings()->get();
        $data['siblings'] = $data['siblings']->filter(function($sibling) {
            return !$sibling->item_id;
        });

        $data['siblings'] = $data['siblings']->pluck('label', 'id');

        $data['siblings']->prepend('-', '');

        $data['dependent_node'] = Commessa::where('execute_after_id', $id)->get()->pluck('id', 'id');

//        Log::info($data['siblings']);
//        Log::info($data['dependent_node']);

        if ($data['dependent_node']->count()) {
            $data['siblings'] = $data['siblings']->filter(function($label, $id) use ($data) {
                return $data['dependent_node']->contains(function($value, $key) use ($id) {
                    // Log::info($key .' '. $id);
                    return $key !== $id;
                });
            });
        }

        $data['title'] = 'Modifica elemento';
        $data['action'] = route('commessa-node.update', $id);

        if ($data['el']->item_id)
            return view('dashboard.commesse.modals.update-node-item', $data);

        return view('dashboard.commesse.modals.create-node', $data);
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
        $el = Commessa::with('root', 'executeAfter', 'parent')->find($id);
        if (!$el) abort('404');

        /** check date **/
        $oldDataInizio = $el->data_inizio_prevista;
        $oldDataFine = $el->data_fine_prevista;
        $oldDataInizioEffettiva = $el->data_inizio_effettiva;
        $oldDataFineEffettiva = $el->data_fine_effettiva;

        /** check costi **/
        $oldCostoPrevisto = $el->costo_previsto;
        $oldCostoEffettivo = $el->costo_effettivo;
        $oldPrezzoCliente = $el->prezzo_cliente;

        $oldStato = $el->stato;

        $validationRules = [
            'label' => 'required',
            // 'dates' => 'required',
            'data_inizio_prevista' => 'required',
            'data_fine_prevista' => 'required|after:data_inizio_prevista',
            'data_inizio_effettiva' => 'sometimes|nullable',
            'data_fine_effettiva' => 'sometimes|nullable|after_or_equal:data_inizio_effettiva',
            'costo_previsto' => 'required|numeric|min:0',
            'prezzo_cliente' => 'required|numeric|min:0',
        ];

        if (!\Illuminate\Support\Facades\Gate::allows('commessa_mod_costi', $el)) {
            unset($validationRules['costo_previsto']);
            unset($validationRules['prezzo_cliente']);
        }

        if ($el->item_id) {
            unset($validationRules['label']);
            // unset($validationRules['dates']);
            // unset($validationRules['data_inizio_prevista']);
            // unset($validationRules['data_fine_prevista']);
            unset($validationRules['prezzo_cliente']);

            if ($el->type != 'materiale') {
                $validationRules['costo_item_giornaliero_previsto'] ='required|numeric|min:0';
                $validationRules['costo_item_orario_previsto'] ='required|numeric|min:0';
            }
        }

        if (!$el->fl_is_data_prevista_changeble) {
            // unset($validationRules['dates']);
            unset($validationRules['data_inizio_prevista']);
            unset($validationRules['data_fine_prevista']);
        }

        if (!$el->fl_is_costo_changeble) {
            unset($validationRules['costo_previsto']);
            unset($validationRules['prezzo_cliente']);
        }

        switch ($request->input('_module', null)) {
            case 'stato':
                $validationRules = [];
                break;
        }

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_note']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'dates':
                    $dates = explode(' - ', $v);
                    $el->data_inizio_prevista = isset($dates[0]) ? strToDate($dates[0])->toDateString() : null;
                    $el->data_fine_prevista = isset($dates[1]) ? strToDate($dates[1])->toDateString() : null;

                    break;
                case 'dates_effettive':
                    $el->data_inizio_effettiva = null;
                    $el->data_fine_effettiva = null;
                    $dates = explode(' - ', $v);
                    if (count($dates) == 2) {
                        $el->data_inizio_effettiva = isset($dates[0]) ? strToDate($dates[0])->toDateString() : null;
                        $el->data_fine_effettiva = isset($dates[1]) ? strToDate($dates[1])->toDateString() : null;
                    }

                    break;
                default:
                    $el->$k = $v;
            }
        }

        if (!$el->data_inizio_effettiva && $el->data_fine_effettiva) {
            $el->data_fine_effettiva = null;
        }

        /** controllo dipendenza loop **/
        if ($request->input('execute_after_id', null)) {
            $node1 = Commessa::find($request->input('execute_after_id'));
            if ($node1->execute_after_id) {
                $node = \App\Models\Commessa::where('id', $node1->execute_after_id)->where('execute_after_id', $el->id)->first();
                if ($node) {
                    return response()->json(['res' => 'error', 'payload' => 'Errore dipendenza, non è possibile scegliere ' . $node1->label . ' rischio di loop'], 422);
                }
            }
        }

        try {

            /** controllo delle date **/
            if (Str::contains($el->type, 'fase_lv')) {
                $root = $el->root;
                $rds = new \Carbon\Carbon($root->data_inizio_prevista);
                $rde = new \Carbon\Carbon($root->data_fine_prevista);

                $ds = new \Carbon\Carbon($el->data_inizio_prevista);
                $de = new \Carbon\Carbon($el->data_fine_prevista);

                if ($ds->startOfDay()->lt($rds->startOfDay())) {
                    throw new CommessaNodeException('La data di inizio non può essere inferiore rispetto a quella della commessa');
                }

                if ($de->startOfDay()->gt($rde->startOfDay())) {
                    throw new CommessaNodeException('La data di fine non può essere superiore rispetto a quella della commessa');
                }
            }

            if ($el->item_id) {
                $parent = $el->parent;
                $rds = new \Carbon\Carbon($parent->data_inizio_prevista);
                $rde = new \Carbon\Carbon($parent->data_fine_prevista);

                $ds = new \Carbon\Carbon($el->data_inizio_prevista);
                $de = new \Carbon\Carbon($el->data_fine_prevista);

                if ($ds->startOfDay()->lt($rds->startOfDay())) {
                    throw new CommessaNodeException('La data di inizio non può essere inferiore rispetto a quella della fase ' . $parent->label);
                }

                if ($de->startOfDay()->gt($rde->startOfDay())) {
                    throw new CommessaNodeException('La data di fine non può essere superiore rispetto a quella della fase ' . $parent->label);
                }
            }

            DB::beginTransaction();

            $el->save();

            switch ($request->input('_module', null)) {
                case 'stato':

                    if ($oldStato === $el->stato) {
                        throw new CommessaNodeException("Non è possibile salvare lo stesso stato consecutivamente");
                    }

                    $cl = new CommessaLog;
                    $cl->id = Str::uuid();
                    $cl->commesse_id = $el->id;
                    $cl->stato = $el->stato;
                    $cl->note = $request->input('_note', null);
                    $cl->username = auth()->user()->name;
                    $cl->save();

                    event(new CommessaNodeChangedStatus($el));
                    break;
            }

            /** Evento date cambiate **/
            if ($oldDataInizioEffettiva != $el->data_inizio_effettiva || $oldDataFineEffettiva != $el->data_fine_effettiva) {

                if ($el->stato !== 'terminata') {
                    return response()->json(['res' => 'error', 'payload' => 'Impossibile salvare le date consuntive, impostare lo stato della fase / sottofase a terminata']);
                }

                $children = collect($el->children);
                $itemIds = $children->reduce(function($ids, $item) {
                    if ($item->item_id) {
                        $ids[$item->item_id] = $item->item_id;
                        return $ids;
                    }
                }, []);

                if (count($itemIds)) {
                    $logItem = CommessaLog::whereIn('item_id', $itemIds)
                        ->where('commesse_id', $el->id)
                        ->where(function($query) use ($el) {
                            $query->where('inizio', '<', $el->data_inizio_effettiva)
                                ->orWhere('fine', '>', $el->data_fine_effettiva);
                        })
                        ->with('item')
                        ->first();

                    if ($logItem) {
                        $range = dataOra($logItem->inizio).' - '.dataOra($logItem->fine);
                        return response()->json(['res' => 'error', 'payload' => 'Date consuntive irregolari: ' . $logItem->item->label . ' possiede un log di lavorazione nel seguente range '.$range]);
                    }
                }

            }

            if ($el->type == 'fase_lv_1' || $el->type == 'fase_lv_2') {
                if ($el->data_inizio_prevista) {
                    if ($oldDataInizio != $el->data_inizio_prevista || $oldDataFine != $el->data_fine_prevista) {
                        // Log::info('A '.$oldDataInizio.' '.$el->data_inizio_prevista);
                        // Log::info('A '.$oldDataFine.' '.$el->data_fine_prevista);
                        event(new CommessaNodeSottoFaseChangedDatePreviste($el));
                    }
                }

                if ($el->data_inizio_effettiva) {
                    if ($oldDataInizioEffettiva != $el->data_inizio_effettiva || $oldDataFineEffettiva != $el->data_fine_effettiva) {
                        // Log::info('B '.$oldDataInizioEffettiva.' '.$el->data_inizio_effettiva);
                        // Log::info('B '.$oldDataFineEffettiva.' '.$el->data_fine_effettiva);
                        event(new CommessaNodeSottoFaseChangedDateEffettive($el));
                    }
                }

                if ($oldCostoPrevisto != $el->costo_previsto || $oldPrezzoCliente != $el->prezzo_cliente || $oldCostoEffettivo != $el->costo_effettivo) {
                    event(new CommessaNodeSottoFaseChangedCosti($el));
                }
            }

            DB::commit();
            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }
        catch (CommessaNodeException $e){
            DB::rollBack();
            $payload = $e->getMessage();
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
        catch (\Exception $e) {
            DB::rollBack();

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
        $el = Commessa::find($id);
        if (!$el) abort(404);

        DB::beginTransaction();
        try {

            DB::table('commesse')->where('execute_after_id', $id)->update(['execute_after_id' => null]);

            event(new CommessaNodeDeleted($el));

            event(new DeleteScadenzaCommessa($el));

            /** Deleting s3 attachments **/
            event(new AttachmentS3ParentDeleted($id, 'commesse'));

            $el->delete();

            DB::commit();

            if ($el->root_id) {
                event(new CommessaRicalculateCosts($el->root_id));
            }

            $payload = 'Cancellazione avvenuta correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            $payload = 'Errore in fase di cancellazione!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function move($id, $versus) {
        $node = Commessa::find($id);
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

    public function statusChange($id) {
        $node = Commessa::find($id);
        if (!$node) abort('404');

        $data['node'] = $node;
        $data['action'] = route('commessa-node.update', [$node->id]);;
        return view('dashboard.commesse.modals.change-node-status', $data);
    }

    public function logs(Request $request, $id) {
        $node = \App\Models\Commessa::with('parent', 'logs')->find($id);
        if (!$node) abort('404');

        $data['node'] = $node;
        if ($node->item_id) {
            if ($request->has('_render_table'))
                return view('dashboard.commesse.analisi.components.item-logs-table', $data);

            if ($node->type == 'materiale')
                return view('dashboard.commesse.modals.get-node-item-materiale-logs', $data);

            return view('dashboard.commesse.modals.get-node-item-logs', $data);
        }

        return view('dashboard.commesse.modals.get-node-logs', $data);
    }

    public function storeLog(Request $request, $id) {

        $node = \App\Models\Commessa::with('parent')->find($id);
        if (!$node) abort('404');

        switch ($node->type) {
            case 'materiale':
                $validationRules = [
                    'item_qty' => 'required|numeric|min:1',
                    'item_costo' => 'required|numeric|min:1'
                ];
                break;
            default:
                $validationRules = [
                    'inizio' => 'required',
                    'fine' => 'required|after:inizio',
                ];
        }

        $validatedData = $request->validate($validationRules);


        $log = new \App\Models\CommessaLog();

        $log->id = Str::uuid();
        $log->commesse_id = $id;

        if ($request->has('inizio')) {
//            $dates = explode(' - ', $request->input('dates'));
//            $log->inizio = isset($dates[0]) ? strToDate($dates[0], 'd/m/Y H:i') : null;
//            $log->fine = isset($dates[1]) ? strToDate($dates[1], 'd/m/Y H:i') : null;

            $log->inizio = $request->input('inizio');
            $log->fine = $request->input('fine');

            if ($node->parent->stato == 'in pausa') {
                return response()->json(['res' => 'error', 'payload' => 'La fase o sottofase di riferimento non è avviata']);
            }

            if ($node->parent->data_inizio_effettiva) {
                $parentDateInizio = new \Carbon\Carbon($node->parent->data_inizio_effettiva);
                $parentDateFine = new \Carbon\Carbon($node->parent->data_fine_effettiva);

                if ($parentDateInizio->gt($log->inizio)) {
                    return response()->json(['res' => 'error', 'payload' => 'Non è possibile inserire una data di inizio minore rispetto a quella consuntiva di fase']);
                }

                if ($parentDateFine->endOfDay()->lt($log->fine)) {
                    return response()->json(['res' => 'error', 'payload' => 'Non è possibile inserire una data di fine maggiore rispetto a quella consuntiva di fase']);
                }
            }
        }

        Log::info($request->all());
        $log->note = $request->input('note', null);
        $log->item_id = $node->item_id;
        $log->item_label = $node->item_label;
        $log->item_qty = $request->input('item_qty', 1);
        $log->item_costo = $request->input('item_costo', 0);
        $log->username = auth()->user()->name;

        if ($log->item_costo > 0) {
            $log->item_costo = $log->item_costo * -1;
        }

        $log->data_attribuzione = $request->input('data_attribuzione', null);

        try {

            $log->save();
            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function extra(Request $request, $id) {
        $el = \App\Models\Commessa::with('root', 'executeAfter', 'parent')->find($id);
        if (!$el) abort('404');

        $extra = json_encode($request->input('extra', null));

        try {
            $el->extra_fields = $extra;
            $el->save();
            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function squadra($id, $squadra_id) {
        $parent = \App\Models\Commessa::with('root')->find($id);
        if (!$parent) abort(404);


        $squadra = \App\Models\Squadra::with('elements')->find($squadra_id);
        if (!$squadra) abort(404);
        foreach ($squadra->elements as $sq) {

            // Log::info($sq->item_id);

            /** controllo esistenza assegnazione **/
            $exist = \App\Models\Commessa::where('item_id', $sq->item_id)
                ->where('parent_id', $parent->id)
                ->count();

            if (!$exist) {
                $el = new \App\Models\Commessa;
                $el->root_id = $parent->root_id;
                $el->day_to_hours = $parent->day_to_hours;

                try {
                    $el->azienda_id = $parent->azienda_id;
                    $el->time = 'h';

                    $el->item_id = $sq->item->id;
                    $el->label = $sq->item->label;
                    $el->type = $sq->item->controller;
                    $el->costo_item_orario_previsto = $sq->costo_item_orario_previsto ?? 0;

                    $el->color = random_color();
                    $el->appendToNode($parent)->save();

                    event(new CommessaNodeInserted($el));

                } catch (\Exception $e) {

                    Log::info($e->getMessage());
                    $payload = 'Errore in fase di salvataggio!';
                    return response()->json(['res' => 'error', 'payload' => $payload]);
                }
            }
        }

        $payload = 'Salvataggio avvenuto correttamente!';
        return response()->json(['res' => 'success','payload' => $payload]);

    }

    public function massiveCopy(Request $request, $id) {

        $validationRules = [
            'item' => 'required',
            'root_id' => 'required'
        ];

        $validatedData = $request->validate($validationRules);

        $commessa = \App\Models\Commessa::find($id);
        if (!$commessa)
            abort(404);

        $commessaFrom = \App\Models\Commessa::where('root_id', $request->input('root_id'))
            ->whereIn('type', ['fase_lv_1', 'fase_lv_2'])
            ->get();

        DB::beginTransaction();
        try {

            foreach ($commessaFrom as $cF) {
                $label = $cF->label;
                $type = $cF->type;
                $c = \App\Models\Commessa::where('root_id', $commessa->id)
                    ->where('label', $label)
                    ->where('type', $type)
                    ->first();

                // Log::info($label);
                // Log::info($type);
                // Log::info($commessa->id);

                if ($c) {

                    Log::info('Trovato');

                    $alreadyExists = \App\Models\Commessa::where('parent_id', $c->id)
                                        ->where('type', $request->input('item'))
                                        ->count();

                    if ($alreadyExists) {
                        throw new \Exception('Esistono già associazioni di tipo: ' . $request->input('item'));
                    }

                    $itemsFrom = \App\Models\Commessa::where('parent_id', $cF->id)
                        ->where('type', $request->input('item'))
                        ->get();

                    foreach ($itemsFrom as $iF) {
                        $el = new \App\Models\Commessa;
                        $el->root_id = $commessa->id;
                        $el->day_to_hours = $iF->day_to_hours;
                        $el->costo_item_giornaliero_previsto = $iF->costo_item_giornaliero_previsto;
                        $el->costo_item_orario_previsto = $iF->costo_item_orario_previsto;
                        $el->costo_previsto = $iF->costo_previsto;
                        $el->azienda_id = $iF->azienda_id;
                        $el->time = $iF->time;
                        $el->item_id = $iF->item_id;
                        $el->label = $iF->label;
                        $el->type = $iF->type;
                        $el->color = $iF->color;

                        $el->appendToNode($c)->save();

                        event(new CommessaNodeInserted($el));
                    }
                }

                // Log::info('--------------------');
            }

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {
            DB::rollBack();

            $payload = 'Errore in fase di salvataggio. ' . $e->getMessage();
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function extrafieldCopy(Request $request, $id) {

        $validationRules = [
            'extra_root_id' => 'required'
        ];

        $validatedData = $request->validate($validationRules);

        $commessa = \App\Models\Commessa::find($id);
        if (!$commessa)
            abort(404);

        $commessaFrom = \App\Models\Commessa::find($request->input('extra_root_id'));
        try {

            Log::info($id);
            if ($commessa->extra_fields) {
                Log::info($commessa->extra_fields);
                throw new \Exception('Extra field già presenti');
            }

            $commessa->extra_fields = $commessaFrom->extra_fields;
            $commessa->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {

            $payload = 'Errore in fase di salvataggio. ' . $e->getMessage();
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function note($id) {
        $data['el'] = \App\Models\Commessa::find($id);
        if (!$data['el']) abort('404');


        $data['title'] = 'Note';
        return view('dashboard.commesse.modals.note-item', $data);
    }
}
