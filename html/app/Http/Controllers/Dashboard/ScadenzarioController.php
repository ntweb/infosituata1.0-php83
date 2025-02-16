<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\AttachmentScadenza;
use App\Models\Azienda;
use App\Models\Commessa;
use App\Models\Gruppo;
use App\Models\InfosituataModuleDetail;
use App\Models\InfosituataModuleDetailScadenza;
use App\Models\Item;
use App\Models\Scadenza;
use App\Services\ScadenzeRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ScadenzarioController extends Controller
{
    protected $scadenzeRepo;

    public function __construct(ScadenzeRepo $scadenzeRepo)
    {
        $this->scadenzeRepo = $scadenzeRepo;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['inScadenza'] = getInscadenza();
        $data['inScadenza'] =  $data['inScadenza']->sortBy(function ($row, $key) {
            return scadeTra($row);
        });


        $data['scaduti'] = getScaduti();
        $data['scaduti'] =  $data['scaduti']->sortBy(function ($row, $key) {
            return scadeTra($row);
        });

        return view('dashboard.scadenzario.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $item = Item::whereRaw("md5(id) = '".$request->get('id')."'")->first();
        if (!$item) abort(404);

        $data = [];
        switch ($item->controller) {
            case 'utente':
                $data = $this->scadenzeRepo->_utente($item);
                break;

            case 'attrezzatura':
                $data = $this->scadenzeRepo->_attrezzatura($item);
                break;

            case 'materiale':
                $data = $this->scadenzeRepo->_materiale($item);
                break;

            case 'mezzo':
                $data = $this->scadenzeRepo->_mezzo($item);
                break;

            case 'risorsa':
                $data = $this->scadenzeRepo->_risorsa($item);
                break;
        }

        // abort(404);
        return view('dashboard.scadenzario.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validationRules = [
            'infosituata_moduli_details_scadenze_id' => 'required',
            'start_at' => 'required|date_format:d/m/Y',
            'end_at' => 'required|date_format:d/m/Y|after:start_at',
            'avvisa_entro_gg' => 'required|numeric|min:0'
        ];

        $validatedData = $request->validate($validationRules);

        $item = Item::find($request->get('item_id'));


        $el = new Scadenza;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'start_at':
                case 'end_at':
                    $el->$k = strToDate($v);
                    break;

                case 'gruppi':
                    break;
                default:
                    $el->$k = $v;
            }
        }


        DB::beginTransaction();
        try {

            $azienda_id = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('azienda_id') : getAziendaId($el);
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;
            $el->item_controller = $item->controller;

            $d = new \Carbon\Carbon($el->end_at);
            $el->advice_at = $d->subDays($el->avvisa_entro_gg);
            $el->created_by = Auth::user()->id;
            $el->updated_by = Auth::user()->id;

            $el->save();

            $gruppiIds = collect($request->get('gruppi', []))->values()->toArray();
            $el->gruppi()->sync($gruppiIds);

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json') {
                $params = [md5($item->id)];
                if ($item->controller == 'risorsa')
                    $params['check_scadenza'] = true;

                return response()->json(['res' => 'success', 'payload' => $payload, '_redirect' => route('infosituata.check', $params)]);
            }

            return redirect()->route('package.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');

        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

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
        $data = $this->scadenzeRepo->edit($id);
        if (!$data) abort(404);

        if ($data['scadenza']) {
            return view('dashboard.scadenzario.edit', $data);
        }

        return view('dashboard.scadenzario.create', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'infosituata_moduli_details_scadenze_id' => 'required',
            'start_at' => 'required|date_format:d/m/Y',
            'end_at' => 'required|date_format:d/m/Y|after:start_at',
            'avvisa_entro_gg' => 'required|numeric|min:0'
        ];

        $validatedData = $request->validate($validationRules);

        $el = Scadenza::find($id);
        if(!$el) abort(404);

        $item = Item::find($el->item_id);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'start_at':
                case 'end_at':
                    $el->$k = strToDate($v);
                    break;

                case 'gruppi':
                    break;
                default:
                    $el->$k = $v;
            }
        }


        DB::beginTransaction();
        try {

            $d = new \Carbon\Carbon($el->end_at);
            $el->advice_at = $d->subDays($el->avvisa_entro_gg);

            $el->updated_by = Auth::user()->id;

            $el->save();

            $gruppiIds = collect($request->get('gruppi', []))->values()->toArray();
            $el->gruppi()->sync($gruppiIds);

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success', 'payload' => $payload, '_redirect' => route('infosituata.check', [md5($item->id)])]);

            return redirect()->route('package.edit', [$el->id]);

        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

    public function check(Request $request, $id)
    {

        $el = Scadenza::with('item')->find($id);
        if(!$el) abort(404);

        if ($el->infosituata_moduli_details_scadenze_id) {
            $dettaglioScad = InfosituataModuleDetailScadenza::find($el->infosituata_moduli_details_scadenze_id);
        }

        DB::beginTransaction();
        try {

            $el->checked_at = \Carbon\Carbon::now();
            $el->checked_by = Auth::user()->id;

            $el->save();

            if ( intval($request->get('_new', '0')) > 0) {
                // Log::info('replico');
                $s = $el->replicate();
                $s->start_at = $el->end_at;

                $dt1 = new \Carbon\Carbon($el->start_at);
                $dt2 = new \Carbon\Carbon($el->end_at);
                $difference = $dt2->diffInDays($dt1);

                $dt1 = new \Carbon\Carbon($s->start_at);
                $s->end_at = $dt1->copy()->addDays($difference);
                if (isset($dettaglioScad)) {
                    if ($dettaglioScad->mesi)
                        $s->end_at = $dt1->copy()->addMonths($dettaglioScad->mesi);

                    if ($dettaglioScad->giorni)
                        $s->end_at = $dt1->copy()->addDays($dettaglioScad->giorni);
                }

                $s->advice_at = new \Carbon\Carbon($s->end_at);
                $s->advice_at->subDays($s->avvisa_entro_gg > 0 ? $s->avvisa_entro_gg : 1);

                $s->checked_at = null;
                $s->checked_by = 0;

                $s->parent_id = $el->id;
                $s->save();

                $gruppiIds = $el->gruppi->pluck('id', 'id');
                $s->gruppi()->sync($gruppiIds);
            }

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';


            if ($el->item_id) {
                $params = [md5($el->item->id)];
                if ($el->item->controller == 'risorsa')
                    $params['check_scadenza'] = true;

                return response()->json(['res' => 'success', 'payload' => $payload, '_redirect' => route('infosituata.check', $params)]);
            }

            return response()->json(['res' => 'success', 'payload' => $payload]);

        }catch (\Exception $e) {
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
    public function destroy(Request $request, $id)
    {
        if(!$request->has('confirm'))
            return redirect()->back()->withInput()->with('error', 'E\' necessario confermare la cancellazione!');

        $el = Scadenza::find($id);
        if (!$el) abort(404);
        DB::beginTransaction();
        try {

            $attachments = AttachmentScadenza::whereScadenzaId($el->id)->get();
            foreach ($attachments as $attachment) {
                $file = public_path('docs/'.$el->azienda_id.'/scadenza/'.$attachment->id);
                File::deleteDirectory($file);
                $attachment->delete();
            }

            $el->delete();
            DB::commit();

            return redirect()->to($request->get('_redirect'))->with('success', 'A breve il sistema cancellerà l\'elemento!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

    public function storeNewTipologiaScadenza(Request $request, $infosituata_moduli_details_id, $azienda_id) {

        $validationRules = [
            'label' => 'required',
            'mesi' => 'required|numeric|min:0',
            'giorni' => 'required|numeric|min:0',
        ];


        $validatedData = $request->validate($validationRules);

        $azienda = Azienda::find($azienda_id);
        if (!$azienda) abort(404);

        $modDetail = InfosituataModuleDetail::find($infosituata_moduli_details_id);
        if (!$modDetail) abort(404);

        $el = new InfosituataModuleDetailScadenza;
        $el->infosituata_moduli_details_id = $infosituata_moduli_details_id;
        $el->azienda_id = $azienda_id;
        $el->label = $request->get('label');
        $el->mesi = $request->get('mesi');
        $el->giorni = $request->get('giorni');

        DB::beginTransaction();
        try {

            $el->save();
            DB::commit();

            $payload = $el;
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

    public function calendar(Request $request) {
        $start = $request->get('start');
        $end = $request->get('end');

        // Log::info($request->all());
        $today = \Carbon\Carbon::today();

        // $scadenze = Scadenza::with(['item', 'detail'])->whereBetween('end_at', [$start, $end])->get();
        $scadenze = getCalendarScadenze($start, $end);

        $scadenzeCommesse = $scadenze->filter(function($c){
            return $c->commesse_id !== 0;
        });

        $scadenze = $scadenze->filter(function($c){
            return $c->item_id !== 0;
        });

        $_ret = $scadenze->map(function ($item, $key) use ($today) {
            $title = $item->item->extras1;
            if ($item->item->extras2) $title .= ' '.$item->item->extras2;

            if ($item->item->controller == 'mezzo') $title .= ' ['.$item->item->extras3.']';

            if ($item->detail)
                $title .= ' | '.$item->detail->label;

            $url = route('scadenzario.edit', [$item->id]);
            $end_at = \Carbon\Carbon::parse($item->end_at);


            $color = null;
            if ($item->checked_at) $color = 'green';
            else if ($end_at->lessThanOrEqualTo($today)) $color = 'red';
            else if ($end_at->diffInDays($today, true) <= 5 && $end_at->diffInDays($today, true) > 0) $color = 'orange';


            return (object) ['title' => $title, 'description' => 'aaaa', 'url' => $url, 'start' => $item->end_at, 'color'=> $color ];
        });


        $_retCommesse = $scadenzeCommesse->map(function ($item, $key) use ($today) {
            $title = '[Commessa]';
            $title .= ' '.$item->commessa->label;

            $title .= ' | '.$item->label;

            $url = route('scadenzario.show-commessa', [$item->id, '_check' => true]);
            $end_at = \Carbon\Carbon::parse($item->end_at);


            $color = null;
            if ($item->checked_at) $color = 'green';
            else if ($end_at->lessThanOrEqualTo($today)) $color = 'red';
            else if ($end_at->diffInDays($today, true) <= 5 && $end_at->diffInDays($today, true) > 0) $color = 'orange';


            return (object) ['title' => $title, 'className' => 'createAvvisoCommessa', 'url' => $url, 'start' => $item->end_at, 'color'=> $color ];
        });

        $scadenzeTasks = getCalendarTasks($start, $end);
        $_retTasks = $scadenzeTasks->map(function ($item, $key) use ($today) {
            $title = '[Task]';
            $title .= ' '.$item->label;


            $url = route('task.assegnati');

            $color = 'silver';
            if ($item->completed_at) {
                $color = 'green';
            }

            return (object) ['title' => $title, 'url' => $url, 'start' => $item->data_inizio_prevista, 'end' => $item->data_fine_prevista, 'color'=> $color ];
        });

        // Log::info($_ret);
        // Log::info($_retCommesse);
        // Log::info($_retTasks);

        $_union = $_ret->union($_retTasks);
        $_union = $_union->union($_retCommesse);


        // Log::info('---------------------');
        // Log::info($_union->all());

        // union between 3 arrays

        return array_merge($_ret->all(), $_retCommesse->all(), $_retTasks->all());



        // return $_ret->values();
    }


    public function commessa($id_commessa) {

        $el = Commessa::find($id_commessa);
        if (!$el) abort(404);

        $data['gruppi'] = Gruppo::whereAziendaId($el->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        $data['readonly'] = false;
        $data['action'] = route('scadenzario.store-commessa', $id_commessa);
        return view('dashboard.scadenzario.modals.commessa', $data);
    }

    public function showCommessa(Request $request, $id_scadenza) {

        $data['el'] = Scadenza::find($id_scadenza);
        if (!$data['el']) abort(404);

        $data['gruppi'] = Gruppo::whereAziendaId($data['el']->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        if ($data['el']) {
            $data['gruppiSel'] = $data['el']->gruppi->pluck('id', 'id');
        }

        $data['readonly'] = $request->has('_check');
        $data['action'] = route('scadenzario.store-commessa',  [$data['el']->commesse_id, 'scadenza_id' => $id_scadenza]);
        return view('dashboard.scadenzario.modals.commessa', $data);
    }

    public function storeCommessa(Request $request, $id_commessa) {

        $validationRules = [
            'label' => 'required',
            'description' => 'required',
            'start_at' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $c = Commessa::find($id_commessa);
        if (!$c) abort(404);


        if ($request->has('scadenza_id'))
            $el = Scadenza::find($request->input('scadenza_id'));
        else {
            $el = new Scadenza;
            $el->adviced = '0';
        }

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_item_id', 'gruppi', 'scadenza_id']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        $d = new \Carbon\Carbon($el->start_at);

        $el->azienda_id = $c->azienda_id;
        $el->item_id = 0;
        $el->item_controller = '0';
        $el->commesse_id = $id_commessa;
        $el->infosituata_moduli_details_id = 0;
        $el->infosituata_moduli_details_scadenze_id = 0;
        $el->end_at = $el->start_at;
        $el->advice_at = $d->subDays($el->avvisa_entro_gg);
        $el->created_by = auth()->user()->id;

        try {

            $el->save();

            $gruppiIds = collect($request->get('gruppi', []))->values()->toArray();
            $el->gruppi()->sync($gruppiIds);

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function destroyCommessa(Request $request, $id)
    {
        $el = Scadenza::find($id);
        if (!$el)
            abort(404);

        /** Cancellazione logica c'è un job che si occupa di fare quella fisica **/
        try {
            $el->delete();
            return response()->json(['res' => 'success', 'message' => 'Cancellazione avvenuta']);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
