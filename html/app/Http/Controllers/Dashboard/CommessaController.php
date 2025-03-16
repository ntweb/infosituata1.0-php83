<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\CommessaNodeInserted;
use App\Events\Illuminate\Events\CommessaNodeSottoFaseChangedCosti;
use App\Events\Illuminate\Events\CommessaRicalculateCosts;
use App\Models\AttachmentCommessa;
use App\Models\AttachmentS3;
use App\Models\Checklist;
use App\Models\Commessa;
use App\Models\CommessaLog;
use App\Models\CommessaRapportino;
use App\Models\CommessaTemplate;
use App\Models\Gruppo;
use App\Models\Scadenza;
use App\Models\Sede;
use App\Models\User;
use App\Models\Utente;
use Carbon\Carbon;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;
use PhpOffice\PhpWord\SimpleType\Border;


class CommessaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('is-commesse-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo COMMESSE permette di definire le proprie commesse, organizzarle e pianificarle in base allo stato occupazionale dei propri dipendenti, mezzi, attrezzature e materiali. Gantt e grafici di supporto, allegati e schedulatori permettono di monitorare in tempo reale lo stato di avanzamento della commessa.';
            return view('layouts.helpers.module-deactive', $data);
        }

        $data['list'] = Commessa::whereNull('type')->with(['cliente'])->paginate(500)->appends(request()->query());
        return view('dashboard.commesse.index', $data);
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

        if (!Gate::allows('can_create_commesse'))
            abort(401);

        $data = [];
        return view('dashboard.commesse.create', $data);
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
            'clienti_id' => 'required',
            'commessa_templates_id' => 'required',
            // 'dates' => 'required',
            'data_inizio_prevista' => 'required',
            'data_fine_prevista' => 'required|after:data_inizio_prevista',
            'day_to_hours' => 'required|numeric|min:0'
        ];

        $validatedData = $request->validate($validationRules);

        $root = CommessaTemplate::where('id', $request->input('commessa_templates_id'))->whereNull('type')->first();
        if (!$root) abort('404');

        $tree = CommessaTemplate::defaultOrder()->descendantsOf($root->id)->toTree();

        DB::beginTransaction();
        try {

            $swapIds = [];

            $attributes = ['azienda_id', 'item_id', 'label', 'item_label', 'type', 'execute_after_id', 'time', 'color'];
            $rootN = new Commessa();
            foreach ($attributes as $attr) {
                switch ($attr) {
                    case 'label':
                        $rootN->$attr = $request->input('label');
                        break;
                    default:
                        $rootN->$attr = $root->$attr;
                }
            }

            /** setto i flag standard del nodo commessa **/
            $rootN->fl_is_status_changeble = '0';
            $rootN->fl_can_have_sottofase = '1';
            $rootN->fl_can_have_item = '0';
            $rootN->fl_send_email_association = $request->input('fl_send_email_association', '1');

            // $rootN->cliente = Str::title($request->input('cliente'));
            $rootN->clienti_id = $request->input('clienti_id');
            $rootN->protocollo = $request->input('protocollo', null);
            $rootN->commesse_template_id = $request->input('commessa_templates_id');

//            $dates = explode(' - ', $request->input('dates'));
            $rootN->data_inizio_prevista = $request->input('data_inizio_prevista');
            $rootN->data_fine_prevista = $request->input('data_fine_prevista');
            $rootN->day_to_hours = $request->input('day_to_hours', 8);
            $rootN->tags = $request->input('tags', null);

            $rootN->extra_fields = $root->extra_fields;

            $rootN->saveAsRoot();
            $swapIds[$root->id] = $rootN->id;

            // Log::info('Saved root');
            // Log::info($rootN->data_inizio_prevista);
            // Log::info($rootN->data_fine_prevista);

            foreach ($tree as $node) {
                $nodeN = new Commessa();
                $nodeN->data_inizio_prevista = $rootN->data_inizio_prevista;
                $nodeN->data_fine_prevista = $rootN->data_fine_prevista;
                $nodeN->root_id = $rootN->id;
                $nodeN->day_to_hours = $rootN->day_to_hours;
                foreach ($attributes as $attr) {
                    $nodeN->$attr = $node->$attr;
                }
                $nodeN->appendToNode($rootN)->save();

                // event(new CommessaNodeInserted($nodeN));

                $swapIds[$node->id] = $nodeN->id;

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
            }



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

            DB::commit();
            return redirect()->route('commessa.edit', $rootN->id);
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
        if (!Gate::allows('is-commesse-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo COMMESSE permette di definire le proprie commesse, organizzarle e pianificarle in base allo stato occupazionale dei propri dipendenti, mezzi, attrezzature e materiali. Gantt e grafici di supporto, allegati e schedulatori permettono di monitorare in tempo reale lo stato di avanzamento della commessa.';
            return view('layouts.helpers.module-deactive', $data);
        }

        $el = Commessa::find($id);
        if (!$el) abort('404');

        if (Gate::denies('commessa_mod_anagrafica', $el)) {
            return redirect()->route('commessa.show', $id);
        }

        $data['el'] = $el;
        $data['tree'] = Commessa::with('executeAfter')->defaultOrder()->descendantsOf($id)->toTree();

        $utentiIds = Utente::get()->pluck('id', 'id');
        $data['users'] = User::whereIn('utente_id', $utentiIds)->select('id', 'name as label')->get()->pluck('label', 'id');
        $data['usersNotificationSel'] = $el->notification_users_ids ? array_flip(json_decode($el->notification_users_ids)) : [];


        return view('dashboard.commesse.create', $data);
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

        $el = Commessa::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module')) {
            case 'map':
            case 'extra-field':
                $validationRules = [];
                break;
            default:
                $validationRules = [
                    'label' => 'required',
                    'clienti_id' => 'required',
                    // 'dates' => 'required',
                    'data_inizio_prevista' => 'required',
                    'data_fine_prevista' => 'required|after:data_inizio_prevista',
                    'day_to_hours' => 'required|numeric|min:0'
                ];
        }

        $validatedData = $request->validate($validationRules);
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_item_id', 'extra', 'google-autocomplete', '_geo_delete']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'dates':
                    $dates = explode(' - ', $v);
                    $el->data_inizio_prevista = strToDate($dates[0]);
                    $el->data_fine_prevista = strToDate($dates[1]);

                    break;
                default:
                    $el->$k = $v;
            }
        }

        try {

            if ($request->input('_geo_delete', '0') == '1') {
                $el->lat = null;
                $el->lng = null;
            }

            if ($request->has('extra')) {
                $el->extra_fields = json_encode($request->input('extra'));
            }

            $el->save();

            DB::table('commesse')->where('root_id', $el->id)->update([
                'day_to_hours' => $el->day_to_hours
            ]);

            DB::table('commesse')->where('root_id', $el->id)->update([
                'costo_item_giornaliero_previsto' =>  DB::raw('costo_item_orario_previsto * day_to_hours')
            ]);


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
        $el = Commessa::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $data['tree'] = Commessa::defaultOrder()->descendantsOf($id)->toTree();

        return view('dashboard.commesse.components.tree', $data);
    }

    public function clienti() {
        $results = DB::table('commesse')
            ->where('azienda_id', getAziendaId())
            ->whereNull('type')
            ->select('cliente')
            ->distinct()
            ->get();

        $clienti = [];
        $index = 0;
        foreach ($results as $el) {
            $clienti[Str::title($el->cliente)] = $index++;
        }

        return response()->json($clienti);
    }

    public function calculateCostiConsuntivi($root_id) {

        DB::beginTransaction();
        try {
            $el = Commessa::find($root_id);
            if (!$el) abort('404');

            $nodes = Commessa::where('root_id', $root_id)
                ->whereIn('type', ['utente', 'mezzo', 'attrezzatura', 'materiale', 'extra'])
                ->get();

            $nodes = $nodes->groupBy('parent_id');
            foreach ($nodes as $node_id => $childrens) {
                $parent = null;
                $parent_costo = 0;
                foreach ($childrens as $child) {
                    $parent = $child->parent;
                    $costo = costoConsuntivoLogItem($child);
                    $parent_costo = $parent_costo + $costo;
                }

                $parent->costo_effettivo = $parent_costo;
                $parent->save();

                event(new CommessaNodeSottoFaseChangedCosti($parent));
            }

            DB::commit();
            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function gantt(Request $request, $id) {
        $commessa = Commessa::find($id);
        if (!$commessa) abort(404);

        /** Calcolo del range **/
        $elements = DB::table('commesse')
            ->where(function($query) use ($id) {
                $query->where('id', $id)->orWhere('root_id', $id);
            })
            ->whereNull('item_id')
            ->get();

        // Log::info($elements);
        $prevRange = $elements->sortBy('data_inizio_prevista');
        $item = $prevRange->first(function($item) {
            return $item->data_inizio_prevista != null;
        });
        $minPrevInizio = $item ? $item->data_inizio_prevista : null;

        $prevRange = $elements->sortByDesc('data_fine_prevista');
        $item = $prevRange->first(function($item) {
            return $item->data_fine_prevista != null;
        });
        $minPrevFine = $item ? $item->data_fine_prevista : null;

        $consRange = $elements->sortBy('data_inizio_effettiva');
        $item = $consRange->first(function($item) {
            return $item->data_inizio_effettiva != null;
        });
        $minConsInizio = $item ? $item->data_inizio_effettiva : null;

        $consRange = $elements->sortByDesc('data_fine_effettiva');
        $item = $consRange->first(function($item) {
            return $item->data_fine_effettiva != null;
        });
        $minConsFine = $item ? $item->data_fine_effettiva : null;

        /*
        Log::info($minPrevInizio);
        Log::info($minPrevFine);
        Log::info($minConsInizio);
        Log::info($minConsFine);
        */

        if ($minConsInizio) {
            $d1 = new \Carbon\Carbon($minPrevInizio);
            $d2 = new \Carbon\Carbon($minConsInizio);
            if ($d2->lt($d1)) {
                $minPrevInizio = $minConsInizio;
            }
        }

        if ($minConsFine) {
            $d1 = new \Carbon\Carbon($minPrevFine);
            $d2 = new \Carbon\Carbon($minConsFine);
            if ($d2->gt($d1)) {
                $minPrevFine = $minConsFine;
            }
        }


        $res['rows'] = $elements->count();
        $res['range'] = [$minPrevInizio, $minPrevFine];
        $res['markers'] = [['value' => date('Y-m-d'), 'color' => 'red', 'label_text' => 'Oggi']];
        $res['series']['prev'] = [];
        $res['series']['cons'] = [];

        $tree = Commessa::with('executeAfter')->defaultOrder()->descendantsOf($id)->toTree();
        foreach ($tree as $fase) {
            $fp = ['name' => Str::limit(Str::title($fase->label), 15, '...'), 'points' => []];
            $fp['points'][] = ['name' => Str::limit(Str::title($fase->label), 15, '...'), 'y' => [$fase->data_inizio_prevista, $fase->data_fine_prevista], 'color' => $fase->color];

            $fc = ['name' => Str::limit(Str::title($fase->label), 15, '...'), 'points' => []];
            $fc['points'][] = ['name' => Str::limit(Str::title($fase->label), 15, '...'), 'y' => [$fase->data_inizio_effettiva ?? $fase->data_inizio_prevista, $fase->data_fine_effettiva ?? $fase->data_fine_prevista], 'color' => $fase->data_inizio_effettiva ? $fase->color : '#e8e8e8'];

            if ($fase->children) {
                foreach ($fase->children as $child) {
                    if (!$child->item_id) {
                        $fp['points'][] = ['name' => Str::limit(Str::title($child->label), 15, '...'), 'y' => [$child->data_inizio_prevista, $child->data_fine_prevista], 'color' => $child->color];
                        $fc['points'][] = ['name' => Str::limit(Str::title($child->label), 15, '...'), 'y' => [$child->data_inizio_effettiva ?? $child->data_inizio_prevista, $child->data_fine_effettiva ?? $child->data_fine_prevista], 'color' => $child->data_inizio_effettiva ? $child->color : '#e8e8e8'];
                    }
                }
            }

            $res['series']['prev'][] = $fp;
            $res['series']['cons'][] = $fc;
            Log::info(json_encode($fc));
        }


        return response()->json($res, 200);
    }

    public function gantt20(Request $request, $id) {

        $data['nodes'] = Commessa::descendantsOf($id)->toTree();

        /** calcolo del range **/
        $elements = DB::table('commesse')
            ->where(function($query) use ($id) {
                $query->where('id', $id)->orWhere('root_id', $id);
            })
            ->whereNull('item_id')
            ->get();


        $data['events'] = [];
        foreach ($elements as $node) {
            $b = new \Carbon\Carbon($node->data_inizio_prevista);
            $e = new \Carbon\Carbon($node->data_fine_prevista);

            // Log::info('-------------');
            // Log::info(Str::title(strtolower($node->label)));
            // Log::info($b);
            // Log::info($e);
            // Log::info($e->diffInDays($b) + 1);

            $data['events'][$node->id]['item_id'] = $node->id;
            $data['events'][$node->id]['lines'][] = [
                'type' => 'p',
                'from' => $node->data_inizio_prevista,
                'to' => $node->data_fine_prevista,
                'days' => abs($e->diffInDays($b)) + 1,
                'title' => Str::title(strtolower($node->label)),
                'bgColor' => $node->color,
                'class' => null
            ];

            if ($node->data_inizio_effettiva) {
                $b = new \Carbon\Carbon($node->data_inizio_effettiva);
                $e = new \Carbon\Carbon($node->data_fine_effettiva);
            }

            $data['events'][$node->id]['lines'][] = [
                'type' => 'c',
                'from' => $node->data_inizio_effettiva ?? $node->data_inizio_prevista,
                'to' => $node->data_fine_effettiva ?? $node->data_fine_prevista,
                'days' => abs($e->diffInDays($b)) + 1,
                'title' => Str::title(strtolower($node->label)),
                'bgColor' => $node->data_inizio_effettiva ? $node->color : '#ffffff',
                'class' => $node->data_inizio_effettiva ? null : 'stripe'
            ];
        }
        $data['events'] = json_encode(array_values($data['events']));


        $prevRange = $elements->sortBy('data_inizio_prevista');
        $item = $prevRange->first(function($item) {
            return $item->data_inizio_prevista != null;
        });
        $minPrevInizio = $item ? $item->data_inizio_prevista : null;

        $prevRange = $elements->sortByDesc('data_fine_prevista');
        $item = $prevRange->first(function($item) {
            return $item->data_fine_prevista != null;
        });
        $minPrevFine = $item ? $item->data_fine_prevista : null;

        $consRange = $elements->sortBy('data_inizio_effettiva');
        $item = $consRange->first(function($item) {
            return $item->data_inizio_effettiva != null;
        });
        $minConsInizio = $item ? $item->data_inizio_effettiva : null;

        $consRange = $elements->sortByDesc('data_fine_effettiva');
        $item = $consRange->first(function($item) {
            return $item->data_fine_effettiva != null;
        });
        $minConsFine = $item ? $item->data_fine_effettiva : null;

        if ($minConsInizio) {
            $d1 = new \Carbon\Carbon($minPrevInizio);
            $d2 = new \Carbon\Carbon($minConsInizio);
            if ($d2->lt($d1)) {
                $minPrevInizio = $minConsInizio;
            }
        }

        if ($minConsFine) {
            $d1 = new \Carbon\Carbon($minPrevFine);
            $d2 = new \Carbon\Carbon($minConsFine);
            if ($d2->gt($d1)) {
                $minPrevFine = $minConsFine;
            }
        }

        $data['range'] = [$minPrevInizio, $minPrevFine];
        $data['period'] = \Carbon\CarbonPeriod::create(new \Carbon\Carbon($minPrevInizio), new \Carbon\Carbon($minPrevFine));

        Log::info($data['events']);
        return view('dashboard.commesse.analisi.components.gantt20', $data);
    }

    public function fasiSelect2(Request $request, $id)
    {
        $t = $request->input('term', null);
        $query = Commessa::whereIn('type', ['fase_lv_1', 'fase_lv_2'])
            ->where('root_id', $id)
            ->orderBy('label');

        if (trim($t) != '') {
            $query = $query->where('label', 'like', '%'.$t.'%');
        }

        $list = $query->get();

        if ($list->count()) {
            $list = $list->map(function ($item){
                return ['id' => $item->id, 'text' => $item->label];
            });
        }

        $data['results'] = $list;
        return response()->json($data);
    }

    public function notifications(Request $request)
    {
        $commessa = Commessa::where('id', $request->input('commesse_root_id'))
            ->whereNull('parent_id')
            ->first();
        if (!$commessa) abort(404);

        try {

            $ids = json_encode($request->input('users_ids', []));
            $commessa->notification_users_ids = $ids;
            $commessa->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);

        }
    }

    public function allegati($id) {
        $el = Commessa::find($id);
        if (!$el) abort(404);

        /** Allegati commessa **/
        $ids = Commessa::where('id', $id)->orWhere('root_id', $id)->get()->pluck('id');
        $data['listAttachmentsCommessa'] = AttachmentS3::whereIn('reference_id', $ids)
            ->where('reference_table', 'commesse')
            ->where('to_delete', '0')
            ->with('node')
            ->get();

        /** Allegati rapportini commessa **/
        $ids = CommessaRapportino::where('commesse_root_id', $id)->get()->pluck('id');
        $data['listAttachmentsCommessaRapportini'] = AttachmentS3::whereIn('reference_id', $ids)
            ->where('reference_table', 'commesse_rapportini')
            ->where('to_delete', '0')
            ->with('rapportino')
            ->get();

        $data['commessa'] = $el;
        return view('dashboard.commesse.analisi.allegati', $data);
    }

    public function avvisi($id) {
        $el = Commessa::find($id);
        if (!$el) abort(404);

        /** Avvisi commessa **/
        $data['listAvvisiCommessa'] = Scadenza::where('commesse_id', $id)
            ->get();

        return view('dashboard.commesse.analisi.avvisi', $data);
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

        // $ids = Commessa::where('id', $id)->orWhere('root_id', $id)->get()->pluck('id');
        $data['allegati'] = AttachmentCommessa::whereIn('commesse_id', $ids)
            ->orderBy('commesse_id')
            ->with('node')
            ->get();


        // Grafico costi
        $ids = Commessa::where('root_id', $id)
            ->whereIn('type', ['materiale', 'extra'])
            ->pluck('id', 'id');
        $costiLogs = CommessaLog::whereIn('commesse_id', $ids)
            ->with('item')
            ->get();
        $groupedLog = $costiLogs->groupBy(function($log) {
            return $log->item ? $log->item->controller : 'extra';
        });

        $costiChart = [];
        foreach ($groupedLog as $key => $logs) {
            $costiChart[$key] = 0;
            foreach ($logs as $log) {
                $costiChart[$key] = $costiChart[$key] + $log->item_costo;
            }
        }

        foreach ($data['flatTree'] as $node) {
            if (in_array($node->type, ['utente', 'mezzo', 'attrezzatura'])) {
                if (!isset($costiChart[$node->type])) {
                    $costiChart[$node->type] = 0;
                }
                $costiChart[$node->type] = $costiChart[$node->type] + costoConsuntivoLogItem($node);
            }
        }

        // order $costiChart values desc
        arsort($costiChart);

        // dd($costiChart);

        $labels = [];
        $values = [];
        foreach ($costiChart as $k => $v) {
            $labels[] = $k;
            $values[] = $v;
        }

        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Costi',
                        'data' => $values
                    ]
                ]
            ]
        ];

        $data['chartUrl'] = 'https://quickchart.io/chart?w=500&h=500&c='.urlencode(json_encode($chartConfig));
        $pdf = PDF::loadView('pdf.commessa.index', $data);

        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('commessa-'.time().'.pdf');
    }

    public function select2(Request $request)
    {
        $t = $request->input('term', null);
        $list = [];
        if (trim($t) != '') {
            $list = Commessa::where('label', 'like', '%'.$t.'%')
                ->whereNull('type')
                ->orderBy('label')
                ->get();
        }
        else {
            $list = Commessa::whereNull('type')
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

    public function qr(Request $request, $format) {
        $generate = urldecode($request->get('generate'));

        $writer = new PngWriter();
        $qrCode = QrCode::create($generate)
            ->setSize(500)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        switch ($format) {
            case 'png':
                $contents = $writer->write($qrCode)->getString();
                break;
            default:
                $contents = $writer->write($qrCode)->getString();
        }

        $filename = time().'_'.Auth::user()->id.'.'.$format;
        $path = public_path('export/qr/'.$filename);
        file_put_contents($path, $contents);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function markIn($id) {

        $data['side'] = 'in';
        $data['commessa'] = Commessa::find($id);
        if (!$data['commessa']) abort('404');

        $data['error'] = null;
        try {
            /** Verifico se il lavoratore è associato ad una fase **/
            $item_id = \auth()->user()->utente_id;
            if (!$item_id) {
                throw new \Exception('Utente non abilitato a timbrare produzioni per questa commessa');
            }

            $item = Utente::find($item_id);
            if (!$item) {
                throw new \Exception('Utente non trovato');
            }

            $data['list'] = Commessa::where('root_id', $id)->where('item_id', $item_id)->with('parent')->get();
            if (!count($data['list'])) {
                throw new \Exception('Utente non associato ad alcuna fase di questa commessa');
            }
            // dd($data['fasi']);

        }
        catch (\Exception $e) {
            $data['error'] = $e->getMessage();
        }

        return view('dashboard.commesse.mark.mark-in', $data);
    }

    public function markOut($id) {

        $data['side'] = 'out';
        $data['commessa'] = Commessa::find($id);
        if (!$data['commessa']) abort('404');

        $data['error'] = null;
        try {
            /** Verifico se il lavoratore è associato ad una fase **/
            $item_id = \auth()->user()->utente_id;
            if (!$item_id) {
                throw new \Exception('Utente non abilitato a timbrare produzioni per questa commessa');
            }

            $item = Utente::find($item_id);
            if (!$item) {
                throw new \Exception('Utente non trovato');
            }

            $data['list'] = Commessa::where('root_id', $id)->where('item_id', $item_id)->with('parent')->get();
            if (!count($data['list'])) {
                throw new \Exception('Utente non associato ad alcuna fase di questa commessa');
            }
            // dd($data['fasi']);

            $now = \Carbon\Carbon::now();

            $ids = Commessa::where('root_id', $id)
                ->where('item_id', $item_id)
                ->get()
                ->pluck('id');

            /**
             * Se ho timbrature in ingresso su altre commesse
             * non chiuse
             * blocco la chiusura
             */
            $fail = DB::table('commesse_log')
                ->where('item_id', $item_id)
                ->whereNotIn('commesse_id', $ids)
                ->whereDate('inizio', $now->toDateString())
                ->where('inizio', '<=', $now)
                ->whereNull('fine')
                ->first();

            if ($fail) {
                $c = Commessa::where('id', $fail->commesse_id)->with('root', 'parent')->first();

                throw new \Exception('Non è possibile chiudere la lavorazione poichè aperta produzione in ' . $c->parent->label . ' della commessa ' . $c->root->label);
            }

            /**
             * chiudo una eventuale timbratura aperta nella stessa giornata
             * sulla stessa commessa
             **/
            $num = DB::table('commesse_log')
                ->where('item_id', $item_id)
                ->whereIn('commesse_id', $ids)
                ->whereNull('fine')
                ->whereDate('inizio', $now->toDateString())
                ->update([
                    'fine' => $now
                ]);

            if (!$num) {
                throw new \Exception('Nessuna timbratura da acquisire');
            }

        }
        catch (\Exception $e) {
            $data['error'] = $e->getMessage();
        }

        return view('dashboard.commesse.mark.mark-out', $data);
    }

    public function storeMark(Request $request, $id) {
        $validationRules = [
            'commessa_id' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $fase = Commessa::where('id', $request->input('commessa_id'))
            ->where('root_id', $id)
            ->first();

        if (!$fase)
            abort(404);

        $item_id = \auth()->user()->utente_id;
        $item = Utente::find($item_id);

        DB::beginTransaction();
        try {

            if ($request->input('side') == 'in') {
                /**
                 * chiudo una eventuale timbratura della stessa giornata
                 * sulla stessa commessa o diverse da parte dello stesso utente
                 **/
                DB::table('commesse_log')
                    ->where('item_id', $item_id)
                    ->whereNull('fine')
                    ->whereDate('inizio', \Carbon\Carbon::now()->toDateString())
                    ->update([
                        'fine' => \Carbon\Carbon::now()
                    ]);
            }

            $uuid = Str::uuid();

            $log = new CommessaLog;
            $log->id = $uuid;
            $log->commesse_id = $fase->id;
            $log->item_id = $item->id;
            $log->item_label = $item->label;

            if ($request->input('side') == 'in')
                $log->inizio = \Carbon\Carbon::now();
            else
                $log->fine = \Carbon\Carbon::now();

            $log->fl_qr = '1';
            $log->username = auth()->user()->name;

            $log->save();

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch(\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function ricalcola($id)
    {
        $el = Commessa::find($id);
        if (!$el) abort('404');

        event(new CommessaRicalculateCosts($el->id));

        $payload = 'Ricalcolo avvenuto correttamente!';
        return response()->json(['res' => 'success','payload' => $payload]);
    }

    public function printGiornaleLavori(Request $request, $id) {

        if (!Gate::allows('is-commesse-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo COMMESSE permette di definire le proprie commesse, organizzarle e pianificarle in base allo stato occupazionale dei propri dipendenti, mezzi, attrezzature e materiali. Gantt e grafici di supporto, allegati e schedulatori permettono di monitorare in tempo reale lo stato di avanzamento della commessa.';
            return view('layouts.helpers.module-deactive', $data);
        }

        $date = $request->input('date');

        // dd($request->all());
        $data['commessa'] = Commessa::with('azienda')->find($id);
        if (!$data['commessa']) abort(404);

        $ids = Commessa::where('root_id', $id)->orWhere('id', $id)->pluck('id', 'id');

        $logs = getCommessaLogByDayItem($ids, $date);
        $groupedLog = $logs->groupBy(function($log) {
            return $log->item ? $log->item->controller : 'extra';
        });

        $fasi = [];
        foreach ($logs as $log) {
            $node = Commessa::find($log->commesse_id);
            $ancestors = Commessa::whereAncestorOf($node)->get();
            foreach ($ancestors as $a) {
                if ($a->type == 'fase_lv_1' || $a->type == 'fase_lv_2') {
                    $fasi[$a->id] = $a;
                }
            }
        }

        $rapportini = CommessaRapportino::where('commesse_root_id', $id)
            ->whereDate('start', $date)
            ->orderBy('commesse_id')
            ->orderBy('start')
            ->with('commessa')
            ->get();

        // dump($fasi);
        // dd('stop');

        // dump($data['commessa']->id);
        // dump($date);
        // dump($logs);
        // dd($groupedLog);

        $data['azienda'] = $data['commessa']->azienda;
        $languageIt = new \PhpOffice\PhpWord\Style\Language(\PhpOffice\PhpWord\Style\Language::IT_IT);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->getSettings()->setThemeFontLang($languageIt);

        // Word configs
        $cellHCentered = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        $cellVCentered = ['valign' => 'center'];
        $cellVMargin = ['cellMarginTop' => 80, 'cellMarginBottom' => 80];
        $cellHMargin = ['cellMarginLeft' => 80, 'cellMarginRight' => 80];
        $paragraphVMargin = [
            'spaceBefore' => 100,
            'spaceAfter' => 100,
        ];
        $paragraphHMargin = [
            'indentation' => ['left' => 100, 'right' => 100]
        ];

        $section = $phpWord->addSection(['marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600]);

        // Header Logo
        $table = $section->addTable(['cellMargin' => 0, 'borderSize' => 0, 'borderStyle' => Border::NONE, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);
        $table->addRow()->addCell()->addText('Inserire logo o intestazione');

        // Titolo commessa
        $table->addRow()->addCell(100 *50)->addText($data['commessa']->label, ['bold' => true, 'size' => 16], ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);

        // Titolo giornale lavori
        $subTableGl = $table->addRow()->addCell(100 * 50)->addTable(['cellMargin' => 0, 'borderSize' => 0, 'borderStyle' => Border::NONE, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);

        // Celle informative Ditta e data compilazione
        $row = $subTableGl->addRow();
        $subTable = $row->addCell(33 * 50)->addTable(array_merge($cellVMargin, ['unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]));
        $subTable->addRow()->addCell(null, ['borderSize' => 0, 'borderStyle' => Border::NONE, 'bgColor' => 'e0e0e0'])->addText("Impresa", ['bold' => true, 'size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);
        $subTable->addRow()->addCell(null,  array_merge(['borderSize' => 0, 'borderStyle' => Border::NONE, $cellVCentered]))->addText($data['azienda']->label, ['bold' => true, 'size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);

        $row->addCell(34 * 50, $cellVCentered)->addText("Giornale dei lavori", ['bold' => true, 'size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);

        $subTable = $row->addCell(33 * 50)->addTable(array_merge($cellVMargin, ['unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]));
        $subTable->addRow()->addCell(null, ['borderSize' => 0, 'borderStyle' => Border::NONE, 'bgColor' => 'e0e0e0'])->addText("Data compilazione", ['bold' => true, 'size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);
        $subTable->addRow()->addCell(null, array_merge(['borderSize' => 0, 'borderStyle' => Border::NONE, $cellVCentered]))->addText(now()->format('d/m/Y'), ['bold' => true, 'size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);
        $subTable->addRow()->addCell(null, array_merge(['borderSize' => 0, 'borderStyle' => Border::NONE, $cellVCentered]))->addText('Rev. N°: ', ['bold' => true, 'size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);

        // informazioni meteo
        $subTable = $table->addRow()->addCell()->addTable(['cellMargin' => 0, 'borderSize' => 0, 'borderStyle' => Border::NONE, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);
        $row = $subTable->addRow();
        $row->addCell(30 * 50, array_merge(['bgColor' => 'e0e0e0']))->addText('Condizioni meteo: ', ['bold' => true, 'size' => 11], $paragraphHMargin);
        $row->addCell(70 * 50)->addText('-', ['size' => 11], $paragraphHMargin);

        // Riferimenti contrattuali
        $subTable = $table->addRow()->addCell()->addTable(['cellMargin' => 0, 'borderSize' => 0, 'borderStyle' => Border::NONE, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);
        $row = $subTable->addRow();
        $row->addCell(100 * 50, ['bgColor' => 'e0e0e0'])->addText('Riferimenti contrattuali', ['bold' => true, 'size' => 11], ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);

        // contratto
        $subTable = $table->addRow()->addCell()->addTable(['cellMargin' => 0, 'borderSize' => 0, 'borderStyle' => Border::NONE, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);
        $row = $subTable->addRow();
        $row->addCell(30 * 50, ['bgColor' => 'e0e0e0'])->addText('Contratto N°: ', ['bold' => true, 'size' => 11], $paragraphHMargin);
        $row->addCell(70 * 50)->addText('-', ['size' => 11], $paragraphHMargin);

        // descrizione intervento
        $subTable = $table->addRow()->addCell()->addTable(['cellMargin' => 0, 'borderSize' => 0, 'borderStyle' => Border::NONE, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);
        $row = $subTable->addRow();
        $row->addCell(30 * 50, ['bgColor' => 'e0e0e0'])->addText('Descrizione intervento: ', ['bold' => true, 'size' => 11], $paragraphHMargin);
        $row->addCell(70 * 50)->addText('-', ['size' => 11], $paragraphHMargin);

        // Data esecuzione
        $d = new \Carbon\Carbon($date);
        $subTable = $table->addRow()->addCell()->addTable(['cellMargin' => 0, 'borderSize' => 0, 'borderStyle' => Border::NONE, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);
        $row = $subTable->addRow();
        $row->addCell(30 * 50, ['bgColor' => 'e0e0e0'])->addText('Data esecuzione lavori: ', ['bold' => true, 'size' => 11], $paragraphHMargin);
        $row->addCell(20 * 50 )->addText($d->format('d-m-Y'), ['size' => 11], $paragraphHMargin);
        $row->addCell(30 * 50, ['bgColor' => 'e0e0e0'])->addText('Turno di lavoro: ', ['bold' => true, 'size' => 11], $paragraphHMargin);
        $row->addCell(20 * 50)->addText('-', ['size' => 11], $paragraphHMargin);

        // Misurazioni
        $text = '';
        foreach ($fasi as $f) {
            $text .= $f->label . "<w:br />\n";
        }
        $table->addRow()->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Descrizioni attività VALUTABILI a misura', ['bold' => true, 'size' => 11], ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);
        $table->addRow()->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText($data['azienda']->label, ['bold' => true, 'size' => 11], ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);
        $table->addRow(1600)->addCell()->addText($text, ['size' => 11], $paragraphHMargin);

        $tableDettagli = $table->addRow()->addCell(100 * 50)->addTable(['cellMargin' => 0, 'borderSize' => 1, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);

        // Utenti
        $row = $tableDettagli->addRow();
        $tableUtenti = $row->addCell(50 * 50)->addTable(['cellMargin' => 0, 'borderSize' => 1, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);
        $row = $tableUtenti->addRow();
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Uomini', ['bold' => true, 'size' => 11], $cellHCentered);

        $row = $tableUtenti->addRow();
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Nominativo', ['bold' => true, 'size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Qualifica', ['bold' => true, 'size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Ore di lavoro', ['bold' => true, 'size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Ore complessive', ['bold' => true, 'size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));

        // ciclo utenti
        if (isset($groupedLog['utente'])) {
            $temp = [];
            foreach ($groupedLog['utente'] as $log ) {
                $h = differenceInMinutes($log->inizio, $log->fine);
                if (isset($temp[$log->item->id])) {
                    // Log::info($temp[$log->item->id]['h'] . ' + ' . $h);
                    $temp[$log->item->id]['h'] = $temp[$log->item->id]['h'] + $h;
                } else {
                    $temp[$log->item->id]['log'] = $log;
                    $temp[$log->item->id]['h'] = $h;
                }
            }

            // dd($temp);
            $total_h = 0;
            foreach ($temp as $log) {
                // dd($log['log']);
                $total_h += $log['h'];
                $row = $tableUtenti->addRow();
                $row->addCell(null)->addText($log['log']->item->extras1.' '.$log['log']->item->extras2, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
                $row->addCell(null)->addText($log['log']->item->user_qualifica_assunzione, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
                $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
                $row->addCell(null)->addText(minutesToHours($log['h']), ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            }

            // Totale
            $row = $tableUtenti->addRow();
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            $row->addCell(null)->addText(minutesToHours($total_h), ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        }
        else {
            // Row blank
            $row = $tableUtenti->addRow();
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        }

        // Attrezzature, mezzi, materiali ed extra
        $row = $tableDettagli->addRow();
        $tableMezziAttrezzatureMateriali= $row->addCell(50 * 50)->addTable(['cellMargin' => 0, 'borderSize' => 1, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);

        $row = $tableMezziAttrezzatureMateriali->addRow();
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Mezzi', ['bold' => true, 'size' => 11], $cellHCentered);

        $row = $tableMezziAttrezzatureMateriali->addRow();
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText(' ', ['bold' => true, 'size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('N°', ['bold' => true, 'size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Ore di lavoro', ['bold' => true, 'size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Ore complessive', ['bold' => true, 'size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));

        // ciclo mezzi
        if (isset($groupedLog['mezzo'])) {
            $temp = [];
            foreach ($groupedLog['mezzo'] as $log ) {
                $h = differenceInMinutes($log->inizio, $log->fine);
                if (isset($temp[$log->item->id])) {
                    // Log::info($temp[$log->item->id]['h'] . ' + ' . $h);
                    $temp[$log->item->id]['h'] = $temp[$log->item->id]['h'] + $h;
                } else {
                    $temp[$log->item->id]['log'] = $log;
                    $temp[$log->item->id]['h'] = $h;
                }
            }

            // dd($temp);
            $total_h = 0;
            foreach ($temp as $log) {
                $total_h += $log['h'];

                $targa = $log['log']->item->extras3 ? '('.$log['log']->item->extras3.')' : '';
                // dd($log['log']);
                $row = $tableMezziAttrezzatureMateriali->addRow();
                $row->addCell(null)->addText($log['log']->item->extras1.' '.$targa, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
                $row->addCell(null)->addText(1, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
                $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
                $row->addCell(null)->addText(minutesToHours($log['h']), ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            }

            // Totale
            $row = $tableMezziAttrezzatureMateriali->addRow();
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            $row->addCell(null)->addText(minutesToHours($total_h), ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        }
        else {
            // Row blank
            $row = $tableMezziAttrezzatureMateriali->addRow();
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        }

        $row = $tableMezziAttrezzatureMateriali->addRow();
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Attrezzature', ['bold' => true, 'size' => 11], $cellHCentered);

        // ciclo attrezzature
        if (isset($groupedLog['attrezzatura'])) {
            $temp = [];
            foreach ($groupedLog['attrezzatura'] as $log ) {
                $h = differenceInMinutes($log->inizio, $log->fine);
                if (isset($temp[$log->item->id])) {
                    // Log::info($temp[$log->item->id]['h'] . ' + ' . $h);
                    $temp[$log->item->id]['h'] = $temp[$log->item->id]['h'] + $h;
                } else {
                    $temp[$log->item->id]['log'] = $log;
                    $temp[$log->item->id]['h'] = $h;
                }
            }

            // dd($temp);
            $total_h = 0;
            foreach ($temp as $log) {
                $total_h += $log['h'];

                $targa = $log['log']->item->extras3 ? '('.$log['log']->item->extras3.')' : '';
                // dd($log['log']);
                $row = $tableMezziAttrezzatureMateriali->addRow();
                $row->addCell(null)->addText($log['log']->item->extras1.' '.$targa, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
                $row->addCell(null)->addText(1, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
                $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
                $row->addCell(null)->addText(minutesToHours($log['h']), ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            }

            // Totale
            $row = $tableMezziAttrezzatureMateriali->addRow();
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            $row->addCell(null)->addText(minutesToHours($total_h), ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        }
        else {
            // Row blank
            $row = $tableMezziAttrezzatureMateriali->addRow();
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        }

        $row = $tableMezziAttrezzatureMateriali->addRow();
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Materiali', ['bold' => true, 'size' => 11], $cellHCentered);

        // ciclo materiale
        if (isset($groupedLog['materiale'])) {
            $temp = [];
            foreach ($groupedLog['materiale'] as $log ) {
                $qty = $log->item_qty;
                if (isset($temp[$log->item->id])) {
                    // Log::info($temp[$log->item->id]['h'] . ' + ' . $h);
                    $temp[$log->item->id]['qty'] = $temp[$log->item->id]['qty'] + $qty;
                } else {
                    $temp[$log->item->id]['log'] = $log;
                    $temp[$log->item->id]['qty'] = $qty;
                }
            }

            // dd($temp);
            $total_qty = 0;
            foreach ($temp as $log) {
                $total_qty += $log['qty'];

                // dd($log['log']);
                $row = $tableMezziAttrezzatureMateriali->addRow();
                $row->addCell(null)->addText($log['log']->item->extras1, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
                $row->addCell(null)->addText($log['qty'], ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
                $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
                $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            }

            // Totale
            $row = $tableMezziAttrezzatureMateriali->addRow();
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText($total_qty, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        }
        else {
            // Row blank
            $row = $tableMezziAttrezzatureMateriali->addRow();
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        }

        $row = $tableMezziAttrezzatureMateriali->addRow();
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Extra', ['bold' => true, 'size' => 11], $cellHCentered);

        // ciclo extra
        if (isset($groupedLog['extra'])) {
            $temp = [];
            foreach ($groupedLog['extra'] as $log ) {
                $qty = $log->item_qty;
                $extraId = strtolower($log->note);
                if (isset($temp[$extraId])) {
                    // Log::info($temp[$log->item->id]['h'] . ' + ' . $h);
                    $temp[$extraId]['qty'] = $temp[$extraId]['qty'] + $qty;
                } else {
                    $temp[$extraId]['log'] = $log;
                    $temp[$extraId]['qty'] = $qty;
                }
            }

            // dd($temp);
            $total_qty = 0;
            foreach ($temp as $log) {
                $total_qty += $log['qty'];

                // dd($log['log']);
                $row = $tableMezziAttrezzatureMateriali->addRow();
                $row->addCell(null)->addText($log['log']->note, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
                $row->addCell(null)->addText($log['qty'], ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
                $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
                $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            }

            // Totale
            $row = $tableMezziAttrezzatureMateriali->addRow();
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText($total_qty, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            $row->addCell(null)->addText('', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        }
        else {
            // Row blank
            $row = $tableMezziAttrezzatureMateriali->addRow();
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin, ['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]));
        }

        // Rapportini
        $tableDettagli = $table->addRow()->addCell(100 * 50)->addTable(['cellMargin' => 0, 'borderSize' => 1, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);

        $row = $tableDettagli->addRow();
        $tableRapportini = $row->addCell(50 * 50)->addTable(['cellMargin' => 0, 'borderSize' => 1, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'width' => 100 * 50]);
        $row = $tableRapportini->addRow();
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Rapportini', ['bold' => true, 'size' => 11], $cellHCentered);

        $row = $tableRapportini->addRow();
        $row->addCell(null, array_merge(['bgColor' => 'e0e0e0'], $cellVCentered))->addText('Etichetta', ['bold' => true, 'size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));

        // ciclo rapportini
        if ($rapportini->count()) {
            foreach ($rapportini as $r) {
                // dd($log['log']);
                $row = $tableRapportini->addRow();
                $row->addCell(null)->addText($r->titolo, ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
            }
        }
        else {
            // Row blank
            $row = $tableRapportini->addRow();
            $row->addCell(null)->addText('-', ['size' => 8], array_merge($paragraphVMargin, $paragraphHMargin));
        }


        $table->addRow(800)->addCell(100 * 50)->addText("Impresa", null, ['spaceBefore' => 100, 'indentation' => ['left' => 100, 'right' => 100]]);

        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $filename = time().'-'.$data['azienda']->id.'-giornale-lavori.docx';
        $objWriter->save(public_path('temp/'.$filename));

        return response()->download(public_path('temp/'.$filename));
    }


}

