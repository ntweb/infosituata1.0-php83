<?php

namespace App\Http\Controllers\Dashboard\Inail;

use App\Exports\ModOt23Export;
use App\Models\InailModOt23;
use App\Scopes\UserIdScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ModOt23Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*
        if(!Gate::allows('can_create_mancati_infortuni'))
            abort(401);
        */

        if($request->has('anno')) {
            if ($request->get('anno') > 2023) {
                return redirect()->route('mod-ot23_2024.index', $request->all());
            }
        }

        $query = InailModOt23::with(['azienda', 'user']);

        if(Gate::allows('can_create_mancati_infortuni_rspp')) {
            $query = InailModOt23::withoutGlobalScope(UserIdScope::class)->with(['azienda', 'user']);
        }

        if($request->has('q')) {
            $query->where(function ($query) use ($request) {
                $query->where('extras1', 'like', '%'.$request->get('q').'%')
                    ->orWhere('extras3', 'like', '%'.$request->get('q').'%');
            });
        }

        $paginate = 50;
        $data['charts'] = null;
        $data['charts_table'] = null;
        $data['export'] = null;
        if($request->has('anno')) {
            if ($request->get('anno') <= 2023) {
                $data['charts'] = true;
            }
            $paginate = 50000;
            $query = $query->whereAnno($request->get('anno'));
            $data['anno'] = $request->get('anno');
            $data['export'] = true;
        }

        $data['list'] = $query
                            ->orderBy('anno', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate($paginate)->appends(request()->query());

        $data['years'] = InailModOt23::distinct('anno')->select('anno')->orderBy('anno', 'desc')->limit(5)->get();



        /**
         * Elaborazione grafici
         */
        if ($data['charts']) {

            // Tipo lavoratore
            $chartTipoLavoratore = collect($data['list']->items())->filter(function($el) { return $el->status == 'active'; })->groupBy('tipo_lavoratore');
            $chartTipoLavoratore = $chartTipoLavoratore->map(function ($item, $key) {
                return collect($item)->count();
            });
            $data['chartTipoLavoratore']['labels'] = join(",",array_keys($chartTipoLavoratore->toArray()));
            $data['chartTipoLavoratore']['values'] = join(",",$chartTipoLavoratore->toArray());
            // dump($data['chartTipoLavoratore']);

            // Tipologia
            $chartTipologia = collect($data['list']->items())->filter(function($el) { return $el->status == 'active'; })->groupBy('tipologia');
            $chartTipologia = $chartTipologia->map(function ($item, $key) {
                return collect($item)->count();
            });
            $data['chartTipologia']['labels'] = join(",",array_keys($chartTipologia->toArray()));
            $data['chartTipologia']['values'] = join(",",$chartTipologia->toArray());
            // dump($data['chartTipologia']);

            // Tipologia Incidente
            $chartTipologiaIncidente = collect($data['list']->items())->filter(function($el) { return $el->status == 'active'; })->groupBy('tipo_incidente');
            $chartTipologiaIncidente = $chartTipologiaIncidente->map(function ($item, $key) {
                return collect($item)->count();
            });
            $data['chartTipologiaIncidente']['labels'] = join(",",array_keys($chartTipologiaIncidente->toArray()));
            $data['chartTipologiaIncidente']['values'] = join(",",$chartTipologiaIncidente->toArray());
            // dump($data['chartTipologiaIncidente']);

            // Reparto
            // $chartReparto = collect($data['list']->items())->groupBy('reparto');
            $chartReparto = collect($data['list']->items())->filter(function($el) { return $el->status == 'active'; })->groupBy(function ($item, $key) {
                return strtolower($item->reparto);
            });
            $chartReparto = $chartReparto->map(function ($item, $key) {
                return collect($item)->count();
            });
            $data['chartReparto']['labels'] = join(",",array_keys($chartReparto->toArray()));
            $data['chartReparto']['values'] = join(",",$chartReparto->toArray());
            // dump($data['chartReparto']);

            // Qualifica
            //$chartQualifica = collect($data['list']->items())->groupBy('qualifica');
            $chartQualifica = collect($data['list']->items())->filter(function($el) { return $el->status == 'active'; })->groupBy(function ($item, $key) {
                return strtolower($item->qualifica);
            });
            $chartQualifica = $chartQualifica->map(function ($item, $key) {
                return collect($item)->count();
            });
            $data['chartQualifica']['labels'] = join(",",array_keys($chartQualifica->toArray()));
            $data['chartQualifica']['values'] = join(",",$chartQualifica->toArray());
            // dump($data['chartQualifica']);

        }

        return view('dashboard.inail.modot23.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        /*
        if(!Gate::allows('can_create_mancati_infortuni'))
            abort(401);
        */


        $data = [];
        $data['_read_only'] = false;

        return view('dashboard.inail.modot23.create', $data);
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
            'data_e_ora' => 'required|date_format:d/m/Y H:i',
            'reparto' => 'required',
            'nome_e_cognome' => 'required',
            'descrizione_incidente' => 'required',
            'preposto' => 'required',
            'prop_elim_pericolo' => 'required',
            'categoria' => 'required',
            'analisi_cause_problema' => 'required',
            'resp_attuazione' => 'required',
            'term_attuazione' => 'required',
            'azioni_da_intr' => 'required',
        ];

        if (!Gate::allows('can-create')) {
            unset($validationRules['preposto']);
            unset($validationRules['categoria']);
            unset($validationRules['analisi_cause_problema']);
            unset($validationRules['resp_attuazione']);
            unset($validationRules['term_attuazione']);
            unset($validationRules['azioni_da_intr']);
        }

        if (!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        $el = new InailModOt23;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;

            if($k == 'data_e_ora')
                $el->$k = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $v);

            if($k == 'categoria')
                $el->$k = join(',', $v);
        }

        DB::beginTransaction();
        try {

            // Log::info($request->all());
            // Log::info('$el->anno ' . $el->anno);
            // dump(InailModOt23::withoutGlobalScope(UserIdScope::class)->get());
            $n = InailModOt23::whereAnno($el->anno)->withoutGlobalScope(UserIdScope::class)->max('n');
            // Log::info('$n ' . $n);
            $el->n = $n + 1;
            // Log::info('$el->n ' . $el->n);

            $el->azienda_id = getAziendaId();
            $el->user_id = Auth::user()->id;
            $el->save();

            DB::commit();

            return redirect()->route('mod-ot23.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio! '.$e->getMessage());
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
        $el = InailModOt23::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $data['_read_only'] = true;
        return view('dashboard.inail.modot23.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*
        if(!Gate::allows('can_create_mancati_infortuni'))
            abort(401);
        */

        $el = InailModOt23::find($id);
        if (!$el) abort('404');

        if ($el->verision == '2024') {
            return redirect()->route('mod-ot23_2024.edit', $id);
        }

        if(!Gate::allows('can-create'))
            return redirect()->route('mod-ot23.show', $id);

        $data['el'] = $el;
        $data['_read_only'] = false;

        return view('dashboard.inail.modot23.create', $data);
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

        $el = InailModOt23::find($id);
        if (!$el) abort('404');

        $validationRules = [
            'data_e_ora' => 'required|date_format:d/m/Y H:i',
            'reparto' => 'required',
            'nome_e_cognome' => 'required',
            'descrizione_incidente' => 'required',
            'preposto' => 'required',
            'prop_elim_pericolo' => 'required',
            'categoria' => 'required',
            'analisi_cause_problema' => 'required',
            'resp_attuazione' => 'required',
            'term_attuazione' => 'required',
            'azioni_da_intr' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;

            if($k == 'data_e_ora')
                $el->$k = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $v);

            if($k == 'categoria')
                $el->$k = join(',', $v);
        }

        DB::beginTransaction();
        try {

            $el->updated_users_id = Auth::user()->id;
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
    public function destroy($id)
    {
        //
    }

    public function analysis(Request $request) {
        return $this->index($request);
    }

    public function export($anno) {

        $query = InailModOt23::whereAnno($anno)->orderBy('created_at')->with(['azienda', 'user']);
        ini_set('memory_limit', '-1');
        return Excel::download(new ModOt23Export($query), Str::slug('modot23-'.$anno).'-'.time().'.xlsx');
    }

    public function pdf($id) {
        $data['el'] = InailModOt23::with('azienda')->find($id);
        if (!$data['el']) abort('404');

        $pdf = PDF::loadView('pdf.inail.mod-ot-23', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('mod-mos-'.time().'.pdf');
    }
}
