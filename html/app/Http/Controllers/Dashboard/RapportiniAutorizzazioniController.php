<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Gruppo;
use App\Models\RapportiniAutorizzazione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RapportiniAutorizzazioniController extends Controller
{

    public static $controllers = [
        'utenti' => [
            'key' => 'utenti',
            'label' => 'Compilazione rapportini utenti',
            'description' => 'Gruppi che possono compilare rapportini utenti'
        ],
        'mezzi' => [
            'key' => 'mezzi',
            'label' => 'Compilazione rapportini mezzi',
            'description' => 'Gruppi che possono compilare rapportini mezzi'
        ],
        'attrezzature' => [
            'key' => 'attrezzature',
            'label' => 'Compilazione rapportini attrezzature',
            'description' => 'Gruppi che possono compilare rapportini attrezzature'
        ],
        'materiali' => [
            'key' => 'materiali',
            'label' => 'Compilazione rapportini materiali',
            'description' => 'Gruppi che possono compilare rapportini materiali'
        ],
        'risorse' => [
            'key' => 'risorse',
            'label' => 'Compilazione rapportini risorse',
            'description' => 'Gruppi che possono compilare rapportini risorse'
        ],
        'rapportini-generica' => [
            'key' => 'rapportini-generica',
            'label' => 'Compilazione rapportini generici',
            'description' => 'Gruppi che possono compilare rapportini generici'
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
            // Log::info($key);
            $el = RapportiniAutorizzazione::firstOrCreate([
                'azienda_id' => getAziendaId(),
                'controller' => $key
            ]);

            $data['gruppiSel'][$key] = [];
            if ($el->gruppi_ids) {
                $auth = json_decode($el->gruppi_ids, true);
                $data['gruppiSel'][$key] = array_flip($auth);
            }
        }

        $gruppiIds = Gruppo::get()->pluck('id', 'id');
        $data['gruppi'] = Gruppo::whereIn('id', $gruppiIds)->select('id', 'label')->get()->pluck('label', 'id');


        return view('dashboard.rapportini.modals.autorizzazioni', $data);
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
        try {

            $controllers = self::$controllers;
            foreach ($controllers as $key => $c) {

                $_ids = $request->input('gruppi_ids.'.$key, []);
                $ids = [];
                foreach ($_ids as $id) {
                    $ids[] = intval($id);
                }

                $ids = json_encode($ids);
                DB::table('rapportini_autorizzazioni')
                    ->where('azienda_id', getAziendaId())
                    ->where('controller', $key)
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
