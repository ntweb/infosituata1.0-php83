<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\AttachmentS3ParentDeleted;
use App\Events\Illuminate\Events\RapportinoCommessaStored;
use App\Events\Illuminate\Events\RapportinoStored;
use App\Exceptions\CommessaNodeException;
use App\Models\ChecklistAutorizzazione;
use App\Models\ChecklistTemplate;
use App\Models\Commessa;
use App\Models\Gruppo;
use App\Models\Item;
use App\Models\Rapportino;
use App\Models\Sede;
use App\Models\User;
use App\Models\Utente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PDF;

class RapportiniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Gate::allows('is-rapportini-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo RAPPORTINI permette di raccogliere informazioni e di associarle a utenti, mezzi, attrezzature, risorse.';
            return view('layouts.helpers.module-deactive', $data);
        }

        $query = Rapportino::orderBy('id', 'desc')
            ->with(['item', 'user']);

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

            return view('dashboard.rapportini.tables.index', $data);
        }

        $data['list'] = $query->paginate(500);
        return view('dashboard.rapportini.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Gate::allows('is-rapportini-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo RAPPORTINI permette di raccogliere informazioni e di associarle a utenti, mezzi, attrezzature, risorse.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if ($request->has('controller')) {
            $controller = $request->input('controller');
            $data['items_id'] =$request->input('items_id', null);
            $data['controller'] = $controller;

            if($data['items_id']) {
                $data['item'] = Item::find($data['items_id']);
                if (!$data['item']) abort(404);

                $data['title'] = $data['item']->label;
            }

            $azienda_id = getAziendaId();

            $data['gruppi'] = Gruppo::whereAziendaId($azienda_id)->orderBy('label')->get()->pluck('label', 'id');
            $data['sedi'] = Sede::whereAziendaId($azienda_id)->orderBy('label')->get()->pluck('label', 'id');
            $data['utenti'] = Utente::with('user')->orderBy('extras1')->get();
            $data['utenti'] = $data['utenti']->filter(function ($value, $key) {
                return $value->user->active == '1';
            })->mapWithKeys(function ($item) {
                return [$item->id => $item->extras1.' '.$item->extras2];
            });

            $data['callback'] = 'reopenLastSavedRapportino';
            $data['formClass'] = 'ns-payload';
            $data['reopenForm'] = 1;

            return view('dashboard.rapportini.modals.rapportini-create', $data);
        }


        $controllers = collect(ModuloRapportiniAutorizzazioniController::getControllers())
            ->reduce(function($c, $item) {

                // Log::info($item['permission']);
                if (Gate::allows('can-create-rapportini', $item['permission']))
                    $c[$item['key']] = $item['label'];

                return $c;
            }, []);

        $data['controllers'] = array_merge(['' => '-'], $controllers);
        return view('dashboard.rapportini.create', $data);
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
            'titolo' => 'required',
            'start' => 'required',
            'descrizione' => 'required',
            'confirm' => 'required',
        ];

        $validatedData = $request->validate($validationRules);


        $controller = 'rapportini-generica';
        if ($request->has('items_id')) {
            $item = Item::find($request->input('items_id'));
            if (!$item) abort(404);

            switch ($item->controller) {
                case 'utente':
                    $controller = 'utenti';
                    break;
                case 'mezzo':
                    $controller = 'mezzi';
                    break;
                case 'attrezzatura':
                    $controller = 'attrezzature';
                    break;
                case 'materiale':
                    $controller = 'materiali';
                    break;
                case 'risorsa':
                    $controller = 'risorse';
                    break;
            }
        }

        $el = new Rapportino();
        $el->controller = $controller;

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'confirm', 'sedi_ids', 'gruppi_ids', 'utenti_ids', 'reopenForm']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                default:
                    $el->$k = $v;
            }
        }


        try {

            $utenti_ids = [];
            if ($request->has('sedi_ids')) {
                $sedi_ids = $request->input('sedi_ids');
                $res = DB::table('sede_item')
                    ->leftJoin('items', 'sede_item.item_id', '=', 'items.id')
                    ->whereController('utente')
                    ->whereIn('sede_id', $sedi_ids)->get()->pluck('item_id');
                foreach ($res as $utente_id) {
                    $utenti_ids[intval($utente_id)] = intval($utente_id);
                }
            }

            // gruppi
            if ($request->has('gruppi_ids')) {
                $gruppi_ids = $request->input('gruppi_ids');
                $res = DB::table('gruppo_utente')->whereIn('gruppo_id', $gruppi_ids)->get()->pluck('utente_id');
                foreach ($res as $utente_id) {
                    $utenti_ids[intval($utente_id)] = intval($utente_id);
                }
            }

            if ($request->has('utenti_ids')) {
                foreach ($request->input('utenti_ids') as $utente_id) {
                    $utenti_ids[intval($utente_id)] = intval($utente_id);
                }
            }

            if (!count($utenti_ids)) {
                return response()->json(['res' => 'error', 'payload' => 'Selezionare i destintari del rapportino'], 400);
            }

            $users_ids = User::whereIn('utente_id', $utenti_ids)->select('id')->pluck('id');
            // Log::info($users_ids);

            $el->azienda_id = getAziendaId();
            $el->users_id = auth()->user()->id;
            $el->username = auth()->user()->name;
            $el->send_to_ids = json_encode($users_ids);
            $el->save();

            event(new RapportinoStored($el));

            $payload = 'Salvataggio avvenuto correttamente!';

            if ($request->has('reopenForm')) {
                $payload = route('rapportini.show', $el->id);
                // Log::info($payload);
                return response()->json($payload);
            }

            return response()->json(['res' => 'success','payload' => $payload]);

        }
        catch (CommessaNodeException $e) {
            return response()->json(['res' => 'error', 'payload' => $e->getMessage()]);
        }
        catch (\Exception $e) {

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
        $rapportino = Rapportino::find($id);
        if (!$rapportino) abort(404);

        $data['id'] = $id;
        $data['action'] = '#';

        if ($rapportino->items_id) {
            $item = Item::find($rapportino->items_id);
            if (!$item) abort(404);

            $data['title'] = $item->label;
            $data['items_id'] = $item->id;
        }

        $data['controller'] = $rapportino->controller;
        $data['el'] = $rapportino;

        $azienda_id = getAziendaId();

        $data['gruppi'] = Gruppo::whereAziendaId($azienda_id)->orderBy('label')->get()->pluck('label', 'id');
        $data['sedi'] = Sede::whereAziendaId($azienda_id)->orderBy('label')->get()->pluck('label', 'id');
        $data['utenti'] = Utente::with('user')->orderBy('extras1')->get();
        $data['utenti'] = $data['utenti']->filter(function ($value, $key) {
            return $value->user->active == '1';
        })->mapWithKeys(function ($item) {
            return [$item->id => $item->extras1.' '.$item->extras2];
        });

        return view('dashboard.rapportini.show', $data);
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
        $el = Rapportino::find($id);
        if (!$el) abort(404);

        try {
            $el->delete();

            /** Deleting s3 attachments **/
            event(new AttachmentS3ParentDeleted($id, 'rapportini'));

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
        $data['action'] = '#';

        /** Commesse **/
        if ($request->has('node')) {
            $node = Commessa::find($request->input('node'));
            if (!$node) abort(404);

            $data['reference_id'] = $node->id;
            $data['reference_controller'] = 'commesse';

            $data['action'] = route('checklist.store');
            $data['callback'] = 'refreshChecklist();';
        }

        /** Items o generica **/
        if ($request->has('items_id')) {
            $item = Item::find($request->input('items_id'));
            if (!$item) abort(404);

            $data['label'] = $item->label;
            $data['reference_id'] = $item->id;
            $data['reference_controller'] = $request->input('reference_controller');
            $data['action'] = route('checklist.store');
            $data['callback'] = 'window.location.replace("'. route('checklist.index') .'");';

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
        $data['r'] = Rapportino::with('azienda')->find($id);
        if (!$data['r']) abort(404);

        $data['azienda'] = $data['r']->azienda;
        $data['users'] = User::whereIn('id', json_decode($data['r']->send_to_ids))->get();

        $pdf = PDF::loadView('pdf.rapportino.index', $data);

        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('rapportino-'.time().'.pdf');
    }
}
