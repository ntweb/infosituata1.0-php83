<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Autorizzazione;
use App\Models\Gruppo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModuloTasksAutorizzazioniController extends Controller
{

    public static $controllers = [
        'tasks_mod' => [
            'key' => 'can_create_tasks',
            'label' => 'Amministrazione task manager',
            'description' => 'Crea o modifica task manager',
            'permission' => 'can_create_tasks'
        ],
        'tasks_mod_template' => [
            'key' => 'can_create_tasks_template',
            'label' => 'Amministrazione tasks - template',
            'description' => 'Crea o modifica i template di task manager',
            'permission' => 'can_create_tasks_template'
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
                'module' => 'tasks',
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

        $data['action'] = action('Dashboard\ModuloTasksAutorizzazioniController@store');
        $data['title'] = 'Autorizzazioni modulo task manager';

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
                    ->where('module', 'tasks')
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
