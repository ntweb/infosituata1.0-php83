<?php

namespace App\Http\Controllers\Dashboard\Inail;

use App\Exports\ModOt23_2024Export;
use App\Models\InailModOt23;
use App\Scopes\UserIdScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ModOt23_2024Controller extends Controller
{
    protected $modot_23_2024_fascia_oraria = [
        '1' => '0-6',
        '2' => '6-12',
        '3' => '12-18',
        '4' => '18-24',
    ];

    protected $modot_23_2024_possibili_cause = [
        '1' => 'Errore procedurale (disattenzione, scarsa conoscenza procedure operative, …)',
        '2' => 'Problema di comunicazione (lingua, incertezza nei ruoli e/o compiti, …)',
        '3' => 'Mancanza/inadeguatezza di procedure operative',
        '4' => 'Mancanza di protezioni sull\'attrezzatura',
        '5' => 'Carenza (inadeguatezza) di protezioni sull\'attrezzatura',
        '6' => 'Anomalia/guasto in avviamento/arresto/esercizio (funzionamento)',
        '7' => 'Unica attrezzatura disponibile ma non idonea alla lavorazione',
        '8' => 'Assenza di attrezzature idonee alla lavorazione',
        '9' => 'Stoccaggio/etichettatura errato di materiali',
        '10' => 'Problema legato alle caratteristiche/trasformazioni di materiali',
        '11' => 'Segnaletica di sicurezza/Cartellonistica inadeguata o assente',
        '12' => 'Assenza o inadeguatezza di percorsi in sicurezza, vie di transito, uscite di emergenza (ingombro di materiali, irregolarità su pavimentazioni, …)',
        '13' => 'Illuminazione non idonea o assente',
        '14' => 'Assenza o inadeguatezza di barriere, protezioni, parapetti, armature',
        '15' => 'Spazi inadeguati su postazioni di lavoro',
        '16' => 'Assenza o inadeguatezza di aree di stoccaggio',
        '17' => 'Presenza imprevista di liquidi (acqua, olio, …)',
        '18' => 'Presenza imprevista di gas, vapori',
        '19' => 'Criticità su impianti generali a supporto dell\'area di lavoro (sistemi di ventilazione, aerazione, …)',
        '20' => 'Presenza di elettricità/linea elettrica accessibile',
        '21' => 'Livelli di rumorosità inadeguati',
        '22' => 'Mancato uso o uso errato di DPI',
        '23' => 'DPI non fornito',
        '24' => 'DPI inadeguato',
        'altro' => 'Altro (specificare)',
    ];

    protected $modot_23_2024_incidente_poss_cause = [
        '1' => 'Caduta dall’alto o in profondità del lavoratore',
        '2' => 'Movimento incoordinato del lavoratore (che provoca urto contro, durante uso di attrezzatura manuale, …)',
        '3' => 'Caduta di gravi',
        '4' => 'Proiezione di solidi',
        '5' => 'Avviamento inatteso/inopportuno di veicolo, macchina, attrezzatura, etc.',
        '6' => 'Collisione/Urto alla guida di mezzo (contro elementi dell\'ambiente di lavoro, altro mezzo)',
        '7' => 'Investimento (anche mancato) da mezzi, veicoli, oggetti in movimento',
        '8' => 'Ribaltamento mezzo (anche mancato)',
        '9' => 'Contatto elettrico diretto/indiretto',
        '10' => 'Esplosioni, Sviluppo di fiamme',
        '11' => 'Fuoriuscita di gas, fumi, aerosol e liquidi',
        '12' => 'Contatto con organi lavoratori in movimento',
        '13' => 'Contatto con oggetti o materiali caldi, fiamme libere, etc. (nella loro abituale sede)',
        '14' => 'Contatto con gas, fumi, aerosol e liquidi (nella loro abituale sede)',
        '15' => 'Contatto con oggetti o materiali a bassissima temperatura (nella loro abituale sede)',
        'altro' => 'Altro (specificare)',
    ];

    protected $modot_23_2024_cause_accertate = [
        '1' => 'Errore procedurale (disattenzione, scarsa conoscenza procedure operative, fretta, …)',
        '2' => 'Problema di comunicazione (lingua, incertezza nei ruoli e/o compiti, …)',
        '3' => 'Mancanza/inadeguatezza di procedure operative',
        '4' => 'Mancanza di protezioni sull\'attrezzatura',
        '5' => 'Carenza (inadeguatezza) di protezioni sull\'attrezzatura',
        '6' => 'Anomalia/guasto in avviamento/arresto/esercizio (funzionamento)',
        '7' => 'Unica attrezzatura disponibile ma non idonea alla lavorazione',
        '8' => 'Assenza di attrezzature idonee alla lavorazione',
        '9' => 'Stoccaggio/etichettatura errato di materiali',
        '10' => 'Problema legato alle caratteristiche/trasformazioni di materiali',
        '11' => 'Segnaletica di sicurezza/Cartellonistica inadeguata o assente',
        '12' => 'Assenza o inadeguatezza di percorsi in sicurezza, vie di transito, uscite di emergenza (ingombro di materiali, irregolarità su pavimentazioni, …)',
        '13' => 'Illuminazione non idonea o assente',
        '14' => 'Assenza o inadeguatezza di barriere, protezioni, parapetti, armature',
        '15' => 'Spazi inadeguati su postazioni di lavoro',
        '16' => 'Assenza o inadeguatezza di aree di stoccaggio',
        '17' => 'Presenza imprevista di liquidi (acqua, olio, …)',
        '18' => 'Presenza imprevista di gas, vapori',
        '19' => 'Criticità su impianti generali a supporto dell\'area di lavoro (sistemi di ventilazione, aerazione, …)',
        '20' => 'Presenza di elettricità/linea elettrica accessibile',
        '21' => 'Livelli di rumorosità inadeguati',
        '22' => 'Mancato uso o uso errato di DPI',
        '23' => 'DPI non fornito',
        '24' => 'DPI inadeguato',
        'altro' => 'Altro (specificare)',
    ];

    protected $modot_23_2024_situazione_presentata = [
        '1' => 'Si frequentemente',
        '2' => 'Si raramente',
        '3' => 'No',
    ];

    protected $modot_23_2024_critic_organizzative = [
        '1' => 'Vigilanza, verifica (monitoraggio), coordinamento',
        '2' => 'Dvr/duvri/psc/pos',
        '3' => 'Formazione e addestramento',
        '4' => 'Sorveglianza sanitaria',
        '5' => 'Primo soccorso',
        '6' => 'Nomine e designazioni',
        '7' => 'Emergenze e antincendio',
        '8' => 'Piani di manutenzione e pulizia',
        '9' => 'Informazione',
        '10' => 'Verifiche periodiche e certificazione conformità impianti',
        '11' => 'Verifica idoneità tecnico-professionale',
    ];

    protected $modot_23_2024_danno = [
        '1' => 'Nessuno',
        '2' => 'Lieve',
        '3' => 'Di media entità',
        '4' => 'Di notevole entità',
    ];

    protected $modot_23_2024_potenziale_danno = [
        '1' => 'Nessuno',
        '2' => 'Lieve',
        '3' => 'Grave',
        '4' => 'Gravissimo',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = InailModOt23::with(['azienda', 'user']);

        if(Gate::allows('can_create_mancati_infortuni_rspp')) {
            $query = InailModOt23::withoutGlobalScope(UserIdScope::class)->with(['azienda', 'user']);
        }

        if ($request->has('q')) {
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
            $data['charts_table'] = true;
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


        if ($data['charts_table']) {
            // dd($data['list']->items());
            $possibili_cause = [];
            $incidente_poss_cause = [];
            $cause_accertate = [];

            foreach ($data['list']->items() as $row) {
                $content = json_decode($row->content, true);
                // dd($content['possibili_cause']);

                foreach ($content['possibili_cause'] ?? [] as $k => $v) {
                    $label = $this->modot_23_2024_possibili_cause[$v];
                    $possibili_cause[$label] = ($possibili_cause[$label] ?? 0) +1;
                }



                foreach ($content['incidente_poss_cause'] ?? [] as $k => $v) {
                    $label = $this->modot_23_2024_incidente_poss_cause[$v];
                    $incidente_poss_cause[$label] = ($incidente_poss_cause[$label] ?? 0) +1;
                }


                foreach ($content['cause_accertate'] ?? [] as $k => $v) {
                    $label = $this->modot_23_2024_cause_accertate[$v];
                    $cause_accertate[$label] = ($cause_accertate[$label] ?? 0) +1;
                }
            }

            arsort($possibili_cause);
            $data['possibili_cause'] = $possibili_cause;

            arsort($incidente_poss_cause);
            $data['incidente_poss_cause'] = $incidente_poss_cause;

            arsort($cause_accertate);
            $data['cause_accertate'] = $cause_accertate;
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
        $data['modot_23_2024_fascia_oraria'] = $this->modot_23_2024_fascia_oraria;
        $data['modot_23_2024_possibili_cause'] = $this->modot_23_2024_possibili_cause;
        $data['modot_23_2024_incidente_poss_cause'] = $this->modot_23_2024_incidente_poss_cause;
        $data['modot_23_2024_cause_accertate'] = $this->modot_23_2024_cause_accertate;
        $data['modot_23_2024_situazione_presentata'] = $this->modot_23_2024_situazione_presentata;
        $data['modot_23_2024_critic_organizzative'] = $this->modot_23_2024_critic_organizzative;
        $data['modot_23_2024_danno'] = $this->modot_23_2024_danno;
        $data['modot_23_2024_potenziale_danno'] = $this->modot_23_2024_potenziale_danno;

        return view('dashboard.inail.modot23.create_2024', $data);
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
            'data_e_ora' => 'required',
            'reparto' => 'required',
            'descrizione_incidente' => 'required',
            'json_possibili_cause' => 'array|min:1|required',
            'json_possibili_cause_altro' => [Rule::requiredIf(in_array('altro', $request->json_possibili_cause ?? []))],
//            'descrizione_finale_evento' => 'required',
//            'json_incidente_poss_cause' => 'array|min:1|required',
//            'json_incidente_poss_cause_altro' => [Rule::requiredIf(in_array('altro', $request->json_incidente_poss_cause ?? []))],
//            'json_cause_accertate' => 'array|min:1|required',
//            'json_cause_accertate_altro' => [Rule::requiredIf(in_array('altro', $request->json_cause_accertate ?? []))],
//            'json_critic_organizzative' => [Rule::requiredIf($request->json_situazione_presentata == 1 || $request->json_situazione_presentata == 2)],
//            'prop_elim_pericolo' => 'required',
        ];

//        if (!Auth::user()->superadmin)
//            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        $el = new InailModOt23;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        $json = [];
        foreach ($fields as $k => $v) {

            if(starts_with($k, 'json_')) {
                $json[str_replace('json_', '', $k)] = $v;
            }
            else {
                $el->$k = $v;
            }

        }

        $el->codice_evento = Str::uuid();
        $el->tipologia = 'nd';
        $el->tipo_incidente = 'nd';
        $el->content = json_encode($json);
        $el->anno = \Carbon\Carbon::parse($el->data_e_ora)->format('Y');
        $el->version = '2024';

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

            return redirect()->route('mod-ot23_2024.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
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

        $el->data_e_ora = substr($el->data_e_ora, 0, 10);

        $data['el'] = $el;
        $data['_read_only'] = true;

        $data['modot_23_2024_fascia_oraria'] = $this->modot_23_2024_fascia_oraria;
        $data['modot_23_2024_possibili_cause'] = $this->modot_23_2024_possibili_cause;
        $data['modot_23_2024_incidente_poss_cause'] = $this->modot_23_2024_incidente_poss_cause;
        $data['modot_23_2024_cause_accertate'] = $this->modot_23_2024_cause_accertate;
        $data['modot_23_2024_situazione_presentata'] = $this->modot_23_2024_situazione_presentata;
        $data['modot_23_2024_critic_organizzative'] = $this->modot_23_2024_critic_organizzative;
        $data['modot_23_2024_danno'] = $this->modot_23_2024_danno;
        $data['modot_23_2024_potenziale_danno'] = $this->modot_23_2024_potenziale_danno;

        $content = json_decode($el->content, true);
        foreach ($content as $k => $v) {
            if (is_array($v)) {
                $v = array_combine($v, $v);
            }
            $data['json_'.$k] = $v;
        }

        return view('dashboard.inail.modot23.show_2024', $data);
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
        if(Gate::allows('can_create_mancati_infortuni_rspp')) {
            $el = InailModOt23::withoutGlobalScope(UserIdScope::class)->find($id);
        }
        else {
            $el = InailModOt23::find($id);
        }



        if (!$el) abort('404');

        if(!Gate::allows('can_create_mancati_infortuni_rspp'))
            return redirect()->route('mod-ot23_2024.show', $id);

        $el->data_e_ora = substr($el->data_e_ora, 0, 10);
        $data['el'] = $el;
        $data['_read_only'] = false;

        $data['modot_23_2024_fascia_oraria'] = $this->modot_23_2024_fascia_oraria;
        $data['modot_23_2024_possibili_cause'] = $this->modot_23_2024_possibili_cause;
        $data['modot_23_2024_incidente_poss_cause'] = $this->modot_23_2024_incidente_poss_cause;
        $data['modot_23_2024_cause_accertate'] = $this->modot_23_2024_cause_accertate;
        $data['modot_23_2024_situazione_presentata'] = $this->modot_23_2024_situazione_presentata;
        $data['modot_23_2024_critic_organizzative'] = $this->modot_23_2024_critic_organizzative;
        $data['modot_23_2024_danno'] = $this->modot_23_2024_danno;
        $data['modot_23_2024_potenziale_danno'] = $this->modot_23_2024_potenziale_danno;

        $content = json_decode($el->content, true);
        foreach ($content as $k => $v) {
            if (is_array($v)) {
                $v = array_combine($v, $v);
            }
            $data['json_'.$k] = $v;
        }

        return view('dashboard.inail.modot23.create_2024', $data);
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
            'data_e_ora' => 'required',
            'reparto' => 'required',
            // 'descrizione_incidente' => 'required',
            // 'json_possibili_cause' => 'array|min:1|required',
            'json_possibili_cause_altro' => [Rule::requiredIf(in_array('altro', $request->json_possibili_cause ?? []))],
            // 'descrizione_finale_evento' => 'required',
            // 'json_incidente_poss_cause' => 'array|min:1|required',
            'json_incidente_poss_cause_altro' => [Rule::requiredIf(in_array('altro', $request->json_incidente_poss_cause ?? []))],
            // 'json_cause_accertate' => 'array|min:1|required',
            'json_cause_accertate_altro' => [Rule::requiredIf(in_array('altro', $request->json_cause_accertate ?? []))],
            'json_critic_organizzative' => [Rule::requiredIf($request->json_situazione_presentata == 1 || $request->json_situazione_presentata == 2)],
            // 'prop_elim_pericolo' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        $json = [];
        foreach ($fields as $k => $v) {

            if(starts_with($k, 'json_')) {
                $json[str_replace('json_', '', $k)] = $v;
            }
            else {
                $el->$k = $v;
            }

        }

        $el->tipo_incidente = 'nd';
        $el->content = json_encode($json);

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
        return Excel::download(new ModOt23_2024Export($query), Str::slug('modot23-'.$anno).'-'.time().'.xlsx');
    }

    public function pdf($id) {
        $data['el'] = InailModOt23::with('azienda')->find($id);
        if (!$data['el']) abort('404');

        $data['el']->data_e_ora = \Carbon\Carbon::parse(substr($data['el']->data_e_ora, 0, 10))->format('d-m-Y');


        $data['modot_23_2024_fascia_oraria'] = $this->modot_23_2024_fascia_oraria;
        $data['modot_23_2024_possibili_cause'] = $this->modot_23_2024_possibili_cause;
        $data['modot_23_2024_incidente_poss_cause'] = $this->modot_23_2024_incidente_poss_cause;
        $data['modot_23_2024_cause_accertate'] = $this->modot_23_2024_cause_accertate;
        $data['modot_23_2024_situazione_presentata'] = $this->modot_23_2024_situazione_presentata;
        $data['modot_23_2024_critic_organizzative'] = $this->modot_23_2024_critic_organizzative;
        $data['modot_23_2024_danno'] = $this->modot_23_2024_danno;
        $data['modot_23_2024_potenziale_danno'] = $this->modot_23_2024_potenziale_danno;

        $content = json_decode($data['el']->content, true);
        foreach ($content as $k => $v) {
            if (is_array($v)) {
                $v = array_combine($v, $v);
            }
            $data['json_'.$k] = $v;
        }

        $pdf = PDF::loadView('pdf.inail.mod-ot-23_2024', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('mod-mos-'.time().'.pdf');
    }
}
