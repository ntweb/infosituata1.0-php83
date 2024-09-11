<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Commessa;
use App\Models\CommessaAutorizzazione;
use App\Models\User;
use App\Models\Utente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommessaAutorizzazioniController extends Controller
{

    protected $controllers = [
        'commessa_mod_anagrafica' => [
            'label' => 'Modifica anagrafica commessa',
            'description' => 'Aggiorna i dati di anagrafica commessa, date previste, tags, ore corrispondenti a giornata lavorativa'
        ],
        'commessa_mod_fasi' => [
            'label' => 'Modifica fasi / sottofasi commessa',
            'description' => 'Inserisci, modifica e cancella fasi e sottofasi della commesa'
        ],
        'commessa_mod_extra_fields' => [
            'label' => 'Modifica extra field commessa',
            'description' => 'Inserisci, modifica e cancella extra fields della commesa'
        ],
        'commessa_update_extra_fields' => [
            'label' => 'Aggiornamento extra field all\'interno della commessa',
            'description' => 'Aggiornamento dei campi extra field all\'interno della commessa, tabella overview'
        ],
        'commessa_mod_autorizzazioni' => [
            'label' => 'Modifica autorizzazioni commessa',
            'description' => 'Accedi e modifica le autorizzazioni ad una commessa'
        ],
        'commessa_mod_date' => [
            'label' => 'Modifica date fasi e sottofasi',
            'description' => 'Modifica le date preventive e consuntive di fasi e sottofasi della commessa'
        ],
        'commessa_mod_costi' => [
            'label' => 'Modifica costi fasi, sottofasi e risorse',
            'description' => 'Modifica costi preventivi e consuntivi delle fasi, sottofasi e risorse'
        ],
        'commessa_mod_stato' => [
            'label' => 'Modifica stati fasi e sottofasi',
            'description' => 'Modifica stati delle fasi e sottofasi'
        ],
        'commessa_view_costi' => [
            'label' => 'Visualizza costi commessa',
            'description' => 'Visualizza qualsiasi costo realtivo a fasi, sottofasi e risorse'
        ],
        'avvisi_create' => [
            'label' => 'Amministrazione avvisi',
            'description' => 'Crea e modifica avvisi con data di scadenza'
        ],
        'rapportini_create' => [
            'label' => 'Crea rapportini',
            'description' => 'Crea rapportini e associazioni alle liste di distribuzione'
        ],
        'risorse_create' => [
            'label' => 'Associa risorse alla commessa',
            'description' => 'Associa risorse a fasi e sottofasi della commessa'
        ],
        'commessa_view_log' => [
            'label' => 'Visualizza log fasi / sottofasi',
            'description' => 'Visualizza i log ed i cambiamenti di stato delle fasi e sottofasi'
        ],
        'risorse_create_log' => [
            'label' => 'Crea log risorse',
            'description' => 'Assegna periodi di lavorazione ad utenti, mezzi, attrezzature e materiali'
        ],
        'risorse_view_log' => [
            'label' => 'Visualizza log risorse',
            'description' => 'Visualizza i log delle lavorazioni degli utenti, mezzi, attrezzature e matariali'
        ],
        'commessa_notify_status' => [
            'label' => 'Assegna notifiche di cambiamento stato fasi / sottofasi',
            'description' => 'Abilita alla selezione degli utenti che riceveranno le notfiche di cambiamento di stato delle fasi / sottofasi'
        ],
        'commessa_uploads' => [
            'label' => 'Upload di allegati',
            'description' => 'Abilita al caricamento di allegati nelle fasi / sottofasi'
        ],
        'commessa_print' => [
            'label' => 'Stampa report commessa',
            'description' => 'Abilita alla stampa della commessa e dei dettagli'
        ],
        'checklist_create' => [
            'label' => 'Crea checklist',
            'description' => 'Crea checklist collegate a fasi / sottofasi'
        ],
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['el'] = Commessa::where('id', $request->input('id'))
            ->whereNull('parent_id')
            ->first();
        if (!$data['el']) abort(404);

        $auth = json_decode($data['el']->auth, true);

        $data['controllers'] = $this->controllers;
        foreach ($data['controllers'] as $key => $c) {
            CommessaAutorizzazione::firstOrCreate([
                'commesse_root_id' => $data['el']->id,
                'autorizzazione' => $key
            ]);

            $data['usersSel'][$key] = isset($auth[$key]) ? array_flip($auth[$key]) : [];
        }

        $utentiIds = Utente::get()->pluck('id', 'id');
        $data['users'] = User::whereIn('utente_id', $utentiIds)->select('id', 'name as label')->get()->pluck('label', 'id');


        return view('dashboard.commesse.modals.autorizzazioni', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $commessa = Commessa::where('id', $request->input('commesse_root_id'))
            ->whereNull('parent_id')
            ->first();
        if (!$commessa) abort(404);

        try {

            foreach ($this->controllers as $key => $c) {
                $ids = json_encode($request->input('users_ids.'.$key, []));
                DB::table('commesse_autorizzazioni')
                    ->where('commesse_root_id', $commessa->id)
                    ->where('autorizzazione', $key)
                    ->update([
                        'users_ids' => $ids
                    ]);
            }

            $auth = $commessa->autorizzazioni()->get();
            $flatMap = $auth->flatMap(function($item) {
                return [$item->autorizzazione => json_decode($item->users_ids)];
            });

            $commessa->auth = json_encode($flatMap);
            $commessa->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);

        }
    }

    public function copy(Request $request)
    {
        $validationRules = [
            'copy_commessa_id' => 'required',
        ];
        $validatedData = $request->validate($validationRules);

        $commessaFrom = Commessa::where('id', $request->input('copy_commessa_id'))->first();
        if (!$commessaFrom)
            abort(404);

        $commessa = Commessa::where('id', $request->input('commesse_root_id'))
            ->whereNull('parent_id')
            ->first();
        if (!$commessa)
            abort(404);

        try {

            $authFrom = CommessaAutorizzazione::where('commesse_root_id', $commessaFrom->id)->get();
            foreach ($authFrom as $af) {
                // Log::info('From...' .$af->commesse_root_id. ' copying.. ' . $af->autorizzazione . ' ids.. '. $af->users_ids .' to...' . $commessa->id);

                $el = CommessaAutorizzazione::firstOrCreate([
                    'commesse_root_id' => $commessa->id,
                    'autorizzazione' => $af->autorizzazione,
                ]);

                $el->users_ids = $af->users_ids;
                $el->save();
            }

            $auth = $commessa->autorizzazioni()->get();
            $flatMap = $auth->flatMap(function($item) {
                return [$item->autorizzazione => json_decode($item->users_ids)];
            });

            $commessa->auth = json_encode($flatMap);
            $commessa->save();

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
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
