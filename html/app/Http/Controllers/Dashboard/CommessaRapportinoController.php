<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\RapportinoCommessaStored;
use App\Exceptions\CommessaNodeException;
use App\Models\Commessa;
use App\Models\CommessaRapportino;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommessaRapportinoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = CommessaRapportino::where('commesse_root_id', $request->input('commesse_root_id'))
            ->orderBy('id')
            ->with('fase')
            ->get();

        $data['list'] = $list;

        return view('dashboard.commesse.analisi.rapportini', $data);
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
        $el = new CommessaRapportino();
        switch ($request->input('_module', null)) {
            case 'utente':
            default:
                $validationRules = [
                    'titolo' => 'required',
                    'start' => 'required',
                    'descrizione' => 'required',
                    'confirm' => 'required',
                    'commesse_id' => 'required',
                ];
                $validatedData = $request->validate($validationRules);

                $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'confirm', 'sedi_ids', 'gruppi_ids', 'utenti_ids']);
                foreach ($fields as $k => $v) {
                    switch ($k) {
                        /*
                        case 'start':
                            $el->$k = strToDate($v);
                            break;
                        */
                        default:
                            $el->$k = $v;
                    }
                }
        }

        $fase = Commessa::find($request->input('commesse_id'));
        if (!$fase) abort(404);

        try {
            /** controllo delle date **/
            $ds = new \Carbon\Carbon($fase->data_inizio_prevista);
            $de = new \Carbon\Carbon($fase->data_fine_prevista);

            if ($fase->data_fine_effettiva) {
                $ds = new \Carbon\Carbon($fase->data_inizio_effettiva);
                $de = new \Carbon\Carbon($fase->data_fine_effettiva);
            }

            $start = new \Carbon\Carbon($el->start);
            if ($start->startOfDay()->gt($de->startOfDay()) || $start->startOfDay()->lt($ds->startOfDay())) {
                throw new CommessaNodeException('La data di riferimento deve essere congrua alle date di previsione / consuntive della fase: ' . $fase->label);
            }


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
                return response()->json(['res' => 'error', 'payload' => 'Selezionare i destintari del rapportino']);
            }

            $users_ids = User::whereIn('utente_id', $utenti_ids)->select('id')->pluck('id');
            // Log::info($users_ids);


            $el->azienda_id = getAziendaId();
            $el->commesse_root_id = $fase->root_id;
            $el->users_id = auth()->user()->id;
            $el->username = auth()->user()->name;
            $el->send_to_ids = json_encode($users_ids);
            $el->save();

            event(new RapportinoCommessaStored($el, $fase));

            $payload = 'Salvataggio avvenuto correttamente!';
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
    public function show(Request $request, $id)
    {
        $data['el'] = CommessaRapportino::with('fase')->find($id);
        if (!$data['el']) abort(404);

        if ($request->has('_modal')) {
            return view('dashboard.commesse.modals.show-rapportino', $data);
        }

        return view('dashboard.commesse.show-rapportino', $data);
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
