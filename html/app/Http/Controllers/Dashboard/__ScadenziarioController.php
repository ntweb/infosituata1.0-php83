<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Azienda;
use App\Models\Gruppo;
use App\Models\InfosituataModule;
use App\Models\InfosituataModuleDetail;
use App\Models\InfosituataModuleDetailScadenza;
use App\Models\Item;
use App\Models\Scadenza;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScadenziarioController extends Controller
{
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

        return view('dashboard.scadenziario.index', $data);
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

        switch ($item->controller) {
            case 'utente':
                return $this->_utente($item);
                break;

            case 'attrezzatura':
                return $this->_attrezzatura($item);
                break;

            case 'mezzo':
                return $this->_mezzo($item);
                break;

            case 'risorsa':
                return $this->_risorsa($item);
                break;
        }

        abort(404);
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
            'infosituata_moduli_details_scadenze_id' => 'required',
            'start_at' => 'required|date_format:d/m/Y',
            'end_at' => 'required|date_format:d/m/Y',
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

            $el->azienda_id = $item->azienda_id;
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
        $scadenza = Scadenza::with('gruppi')->whereId($id)->first();
        if (!$scadenza) abort(404);

        $item = Item::find($scadenza->item_id);
        if (!$item) abort(404);

        switch ($item->controller) {
            case 'utente':
                return $this->_utente($item, $scadenza);
                break;

            case 'attrezzatura':
                return $this->_attrezzatura($item, $scadenza);
                break;

            case 'mezzo':
                return $this->_mezzo($item, $scadenza);
                break;

            case 'risorsa':
                return $this->_risorsa($item, $scadenza);
                break;
        }

        abort(404);
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
            'end_at' => 'required|date_format:d/m/Y',
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

        DB::beginTransaction();
        try {

            $el->checked_at = \Carbon\Carbon::now();
            $el->checked_by = Auth::user()->id;

            $el->save();

            if ( intval($request->get('_new', '0')) > 0) {
                $s = $el->replicate();
                $s->start_at = $el->end_at;

                $dt1 = new \Carbon\Carbon($el->start_at);
                $dt2 = new \Carbon\Carbon($el->end_at);
                $difference = $dt2->diffInDays($dt1);

                $dt1 = new \Carbon\Carbon($s->start_at);
                $s->end_at = $dt1->copy()->addDays($difference);
                $s->advice_at = new \Carbon\Carbon($s->end_at);
                $s->advice_at->subDay($s->avvisa_entro_gg);

                $s->checked_at = null;
                $s->checked_by = 0;

                $s->parent_id = $el->id;
                $s->save();

                $gruppiIds = $el->gruppi->pluck('id', 'id');
                $s->gruppi()->sync($gruppiIds);
            }

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';


            $params = [md5($el->item->id)];
            if ($el->item->controller == 'risorsa')
                $params['check_scadenza'] = true;

            return response()->json(['res' => 'success', 'payload' => $payload, '_redirect' => route('infosituata.check', $params)]);

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
    public function destroy($id)
    {
        //
    }

    public function storeNewTipologiaScadenza(Request $request, $infosituata_moduli_details_id, $azienda_id) {

        $validationRules = [
            'label' => 'required',
            'mesi' => 'required|numeric|min:0',
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

        $scadenze = Scadenza::with(['item', 'detail'])->whereBetween('end_at', [$start, $end])->get();
        $_ret = $scadenze->map(function ($item, $key){
            $title = $item->item->extras1;
            if ($item->item->extras2) $title .= ' '.$item->item->extras2;

            if ($item->detail)
                $title .= ' | '.$item->detail->label;

            $url = route('scadenziario.edit', [$item->id]);

            return (object) ['title' => $title, 'description' => 'aaaa', 'url' => $url, 'start' => $item->end_at, 'color'=> $item->checked_at ? 'green' : null ];
        });

        // Log::info($_ret);

        return $_ret;
    }

    private function _utente($item, $scadenza = null) {
        $modulo = InfosituataModule::with('details')->whereModule($item->controller)->first();
        if (!$modulo) abort(404);
        $data['moduli_details'] = $modulo->details()->with('scadenze')->get();

        $data['item'] = $item;
        $data['scadenza'] = $scadenza;
        $data['gruppi'] = Gruppo::whereAziendaId($item->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        if ($scadenza) {
            $data['gruppiSel'] = $scadenza->gruppi->pluck('id', 'id');
            return view('dashboard.scadenziario.edit', $data);
        }

        return view('dashboard.scadenziario.create', $data);
    }

    private function _attrezzatura($item, $scadenza = null) {
        $modulo = InfosituataModule::with('details')->whereModule('attrezzature')->first();
        if (!$modulo) abort(404);
        $data['moduli_details'] = $modulo->details()->with('scadenze')->get();

        $data['item'] = $item;
        $data['scadenza'] = $scadenza;
        $data['gruppi'] = Gruppo::whereAziendaId($item->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        if ($scadenza) {
            $data['gruppiSel'] = $scadenza->gruppi->pluck('id', 'id');
            return view('dashboard.scadenziario.edit', $data);
        }

        return view('dashboard.scadenziario.create', $data);
    }

    private function _mezzo($item, $scadenza = null) {
        $modulo = InfosituataModule::with('details')->whereModule('attrezzature')->first();
        if (!$modulo) abort(404);
        $data['moduli_details'] = $modulo->details()->with('scadenze')->get();

        $data['item'] = $item;
        $data['scadenza'] = $scadenza;
        $data['gruppi'] = Gruppo::whereAziendaId($item->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        if ($scadenza) {
            $data['gruppiSel'] = $scadenza->gruppi->pluck('id', 'id');
            return view('dashboard.scadenziario.edit', $data);
        }

        return view('dashboard.scadenziario.create', $data);
    }

    private function _risorsa($item, $scadenza = null) {
        $modulo = InfosituataModule::with('details')->whereModule('risorse')->first();
        if (!$modulo) abort(404);
        $data['moduli_details'] = $modulo->details()->with('scadenze')->get();

        $data['item'] = $item;
        $data['scadenza'] = $scadenza;
        $data['gruppi'] = Gruppo::whereAziendaId($item->azienda_id)->whereBroadcast('1')->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        if ($scadenza) {
            $data['gruppiSel'] = $scadenza->gruppi->pluck('id', 'id');
            return view('dashboard.scadenziario.edit', $data);
        }

        return view('dashboard.scadenziario.create', $data);
    }
}
