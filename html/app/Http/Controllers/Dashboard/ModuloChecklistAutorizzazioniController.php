<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Autorizzazione;
use App\Models\Gruppo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModuloChecklistAutorizzazioniController extends Controller
{

    public static $controllers = [
        'utenti' => [
            'key' => 'utenti',
            'label' => 'Compilazione checklist utenti',
            'description' => 'Gruppi che possono compilare checklist utenti',
            'permission' => 'can_create_utenti_checklist'
        ],
        'mezzi' => [
            'key' => 'mezzi',
            'label' => 'Compilazione checklist mezzi',
            'description' => 'Gruppi che possono compilare checklist mezzi',
            'permission' => 'can_create_mezzi_checklist'
        ],
        'attrezzature' => [
            'key' => 'attrezzature',
            'label' => 'Compilazione checklist attrezzature',
            'description' => 'Gruppi che possono compilare checklist attrezzature',
            'permission' => 'can_create_attrezzature_checklist'
        ],
        'materiali' => [
            'key' => 'materiali',
            'label' => 'Compilazione checklist materiali',
            'description' => 'Gruppi che possono compilare checklist materiali',
            'permission' => 'can_create_materiali_checklist'
        ],
        'risorse' => [
            'key' => 'risorse',
            'label' => 'Compilazione checklist risorse',
            'description' => 'Gruppi che possono compilare checklist risorse',
            'permission' => 'can_create_risorse_checklist'
        ],
        'checklist-generica' => [
            'key' => 'checklist-generica',
            'label' => 'Compilazione checklist generica',
            'description' => 'Gruppi che possono compilare checklist generiche',
            'permission' => 'can_create_checklist-generica_checklist'
        ],

    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // Log::info(getAziendaId());

        $data['controllers'] = self::$controllers;

        /** Aggiungo la gestione dei template **/
        $data['controllers']['template'] = [
            'key' => 'checklist-template',
            'label' => 'Amministrazione dei template di checklist',
            'description' => 'Gruppi che possono creare o modificare template',
            'permission' => 'can_create_template_checklist'
        ];

        foreach ($data['controllers'] as $key => $c) {
            $el = Autorizzazione::firstOrCreate([
                'azienda_id' => getAziendaId(),
                'module' => 'checklist',
                'reference_controller' => $key,
                'permission' => $c['permission'],
            ]);

            $data['gruppiSel'][$key] = [];
            if ($el->gruppi_ids) {
                $auth = json_decode($el->gruppi_ids, true);
                $data['gruppiSel'][$key] = array_flip($auth);
            }
        }

        $gruppiIds = Gruppo::get()->pluck('id', 'id');
        $data['gruppi'] = Gruppo::whereIn('id', $gruppiIds)->select('id', 'label')->get()->pluck('label', 'id');

        $data['action'] = route('mod-che-aut.store');
        $data['title'] = 'Autorizzazioni modulo checklist';

        return view('dashboard.autorizzazioni.modals.autorizzazioni', $data);

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

        $controllers = self::getControllers();

        /** Aggiungo la gestione dei template **/
        $controllers['template'] = [
            'key' => 'checklist-template',
            'label' => 'Amministrazione dei template di checklist',
            'description' => 'Gruppi che possono creare o modificare template',
            'permission' => 'can_create_template_checklist'
        ];

        try {

            foreach ($controllers as $key => $c) {

                $_ids = $request->input('gruppi_ids.'.$key, []);
                $ids = [];
                foreach ($_ids as $id) {
                    $ids[] = intval($id);
                }

                $ids = json_encode($ids);
                DB::table('autorizzazioni')
                    ->where('azienda_id', getAziendaId())
                    ->where('module', 'checklist')
                    ->where('reference_controller', $key)
                    ->update([
                        'gruppi_ids' => $ids
                    ]);
            }

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

    static function getControllers() {
        return self::$controllers;

    }
}
