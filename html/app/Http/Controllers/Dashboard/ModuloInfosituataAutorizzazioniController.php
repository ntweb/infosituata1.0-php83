<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Autorizzazione;
use App\Models\Gruppo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModuloInfosituataAutorizzazioniController extends Controller
{

    public static $controllers = [
        'infosituata_utenti' => [
            'key' => 'infosituata_utenti',
            'label' => 'Amministrazione utenti',
            'description' => 'Creazione, modifica degli utenti e scadenze associate',
            'permission' => 'can_create_utenti'
        ],
        'infosituata_mezzi' => [
            'key' => 'infosituata_mezzi',
            'label' => 'Amministrazione mezzi',
            'description' => 'Creazione, modifica dei mezzi e scadenze associate',
            'permission' => 'can_create_mezzi'
        ],
        'infosituata_mezzi_manutenzioni' => [
            'key' => 'infosituata_mezzi_manutenzioni',
            'label' => 'Amministrazione mezzi - manutenzioni',
            'description' => 'Creazione e modifica schede manutenzione mezzi',
            'permission' => 'can_create_manutenzione_mezzi'
        ],
        'infosituata_mezzi_controlli' => [
            'key' => 'infosituata_mezzi_controlli',
            'label' => 'Amministrazione mezzi - controlli',
            'description' => 'Creazione e modifica schede controllo mezzi',
            'permission' => 'can_create_controllo_mezzi'
        ],
        'infosituata_mezzi_sc_carburante' => [
            'key' => 'infosituata_mezzi_sc_carburante',
            'label' => 'Amministrazione mezzi - schede carburante',
            'description' => 'Creazione e modifica schede carburante mezzi',
            'permission' => 'can_create_sc_carburante_mezzi'
        ],
        'infosituata_attrezzature' => [
            'key' => 'infosituata_attrezzature',
            'label' => 'Amministrazione attrezzature',
            'description' => 'Creazione, modifica delle attrezzature e scadenze associate',
            'permission' => 'can_create_attrezzature'
        ],
        'infosituata_attrezzature_manutenzioni' => [
            'key' => 'infosituata_attrezzature_manutenzioni',
            'label' => 'Amministrazione attrezzature - manutenzioni',
            'description' => 'Creazione e modifica schede manutenzione attrezzature',
            'permission' => 'can_create_manutenzione_attrezzature'
        ],
        'infosituata_attrezzature_controlli' => [
            'key' => 'infosituata_attrezzature_controlli',
            'label' => 'Amministrazione attrezzature - controlli',
            'description' => 'Creazione e modifica schede controllo attrezzature',
            'permission' => 'can_create_controllo_attrezzature'
        ],
        'infosituata_materiali' => [
            'key' => 'infosituata_materiali',
            'label' => 'Amministrazione materiali',
            'description' => 'Creazione, modifica delle materiali e scadenze associate',
            'permission' => 'can_create_materiali'
        ],
        'infosituata_risorse' => [
            'key' => 'infosituata_risorse',
            'label' => 'Amministrazione risorse',
            'description' => 'Creazione, modifica delle risorse e scadenze associate',
            'permission' => 'can_create_risorse'
        ],
        'infosituata_tip_scadenza' => [
            'key' => 'infosituata_tip_scadenza',
            'label' => 'Amministrazione tipologie scadenze',
            'description' => 'Creazione e modifica delle tipologie di scadenza',
            'permission' => 'can_create_tip_scadenza'
        ],
        'infosituata_eventi' => [
            'key' => 'infosituata_eventi',
            'label' => 'Amministrazione eventi',
            'description' => 'Creazione e modifica degli eventi',
            'permission' => 'can_create_eventi'
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
        foreach ($data['controllers'] as $key => $c) {
            $el = Autorizzazione::firstOrCreate([
                'azienda_id' => getAziendaId(),
                'module' => 'infosituata',
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


        $data['action'] = route('mod-inf-aut.store');
        $data['title'] = 'Autorizzazioni modulo Infosituata';

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
                    ->where('module', 'infosituata')
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
