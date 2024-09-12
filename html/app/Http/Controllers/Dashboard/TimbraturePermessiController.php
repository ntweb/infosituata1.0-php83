<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\TimbraturaModified;
use App\Events\Illuminate\Events\TimbraturaPermessoUpdated;
use App\Models\TimbraturaPermesso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TimbraturePermessiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('is-user-timbrature-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Rilevazione presenze consente di timbrare le entrate e le uscite
            degli utenti geolocalizzando il punto in cui le stesse sono state effettuate.<br>
            È possibile estrapolare i dati di timbratura, normalizzare le anomalie ed esportare le liste filtrate per giornata in excel';
            return view('layouts.helpers.module-deactive', $data);
        }

        if ($request->has('_user')) {
            // ritorna la lista degli ultimi 50 giustificativi
            $data['list'] = TimbraturaPermesso::where('users_id', auth()->user()->id)->orderBy('id', 'desc')->limit(50)->get();

            // mostra il form per l'inserimento di un nuovo giustificativo
            return view('dashboard.timbrature-permessi.index-user', $data);
        }

        if ($request->has('_search')) {
            $data['list'] = [];

            if ($request->has('users_id') || $request->has('start_at') || $request->has('end_at')) {
                $query = TimbraturaPermesso::with('user')
                    ->orderBy('start_at');

                if ($request->input('users_id', null)) {
                    $query = $query->where('users_id', $request->input('users_id'));
                }

                if ($request->input('start_at', null) || $request->input('end_at', null)) {
                    $start_at = new \Carbon\Carbon($request->input('start_at'));
                    $end_at = new \Carbon\Carbon($request->input('end_at'));

                    $query = $query->where(function($q) use ($start_at, $end_at) {
                        $start = $start_at->startOfDay()->toDateTimeString();
                        $end = $end_at->endOfDay()->toDateTimeString();

                        // Log::info($start);
                        // Log::info($end);

                        $q->whereBetween('start_at', [$start, $end])
                            ->orWhereBetween('end_at', [$start, $end])
                            ->orWhere(function($q) use ($start, $end) {
                                $q->whereDate('start_at', '>=', $start)->whereDate('end_at', '<=', $end);
                            });
                    });
                }

                $data['list'] = $query->get();
                $data['export'] = true;
                // Log::info($query->toSql());

                if ($request->has('export')) {
                    return $this->export($data);
                }
            }

            return view('dashboard.timbrature-permessi.tables.power-user-index', $data);
        }


        $query = TimbraturaPermesso::with('user')
            ->orderBy('id', 'desc');

        if ($request->has('_only_in_attesa')) {
            $query = $query->whereNull('status');
        }
        else {
            $query = $query->limit(50);
        }


        $data['list'] = $query->get();

        if ($request->has('_table')) {
            return view('dashboard.timbrature-permessi.tables.power-user-index', $data);
        }

        return view('dashboard.timbrature-permessi.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = [];

        if ($request->has('_power_user')) {
            return view('dashboard.timbrature-permessi.modals.permesso', $data);
        }
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
            'type' => 'required',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
        ];

        if ($request->has('users_id')) {
            $validationRules['users_id'] = 'required';
            $validationRules['status'] = 'required';
        }

        if ($request->input('type', '') == 'malattia') {
            if(!Gate::allows('can-create')) {
                // abort(301, 'La malattia può essere inserita solo da Power User');
                $payload = 'La malattia può essere inserita solo da Power User';
                if ($request->get('_type') == 'json')
                    return response()->json(['res' => 'error', 'payload' => $payload]);

                return redirect()->back()->withInput()->with('error', $payload);
            }
        }


        if ($request->input('type', '') == 'permesso giornaliero') {
            unset($validationRules['end_at']);
        }

        $validatedData = $request->validate($validationRules);

        if ($request->input('type', '') == 'permesso orario') {
            $s = new \Carbon\Carbon($request->input('start_at'));
            $e = new \Carbon\Carbon($request->input('end_at'));

            if ($s->startOfDay() != $e->startOfDay()) {

                $payload = 'Il giorno di richiesta per il permesso orario deve essere unico';
                if ($request->get('_type') == 'json')
                    return response()->json(['res' => 'error', 'payload' => $payload]);

                return redirect()->back()->withInput()->with('error', $payload);
            }
        }

        $el = new TimbraturaPermesso();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_user', '_power_user']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        try {
            $el->users_id = \auth()->user()->id;
            if ($request->has('users_id'))
                $el->users_id = $request->input('users_id');


            $el->azienda_id = getAziendaId();
            $el->requested_at = \Carbon\Carbon::now();

            $el->save();

            event(new TimbraturaPermessoUpdated($el));

            $parameters = null;
            if ($request->has('_user')) {
                $parameters = ['_user' => true];
            }

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);

            return redirect()->to(route('timbrature-permessi.index', $parameters));
        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

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
        $data['el'] = TimbraturaPermesso::with('user')->find($id);
        if (!$data['el']) abort('404');

        return view('dashboard.timbrature-permessi.modals.permesso', $data);
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
        $el = TimbraturaPermesso::with('user')->find($id);
        if (!$el) abort('404');

        $validationRules = [
            'status' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        DB::beginTransaction();
        try {

            $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_user']);
            foreach ($fields as $k => $v) {
                $el->$k = $v;
            }

            $el->updated_by = auth()->user()->id;

            $el->save();

            event(new TimbraturaPermessoUpdated($el));

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
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
        $el = TimbraturaPermesso::find($id);
        if (!$el) abort(404);

        try {

            $el->delete();

            $payload = 'Cancellazione avvenuta correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di cancellazione!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function export($data) {
        $filename = uniqid().'-Export-permessi-'.date('d-m-y').'.xlsx';

        $azienda = getAziendaBySessionUser();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata - " . $azienda->label);
        $spreadsheet->getProperties()->setDescription("Permessi");

        // indice generico
        $i=1;

        // descrivo i criteri selezionati
        $spreadsheet->getActiveSheet()->SetCellValue("A$i", "Azienda: " . $azienda->label . " - Permessi");

        $i++;

        $celle = array(
            "A"=>"Utente",
            "B"=>"Tipologia",
            "C"=>"Da",
            "D"=>"A",
            "E"=>"Note operatore",
        );
        $i++;
        foreach ($celle as $k => $v){
            $spreadsheet->getActiveSheet()->SetCellValue("$k$i", $v);
            $spreadsheet->getActiveSheet()->getStyle("$k$i")->applyFromArray(
                array(
                    'font'    => array(
                        'name'      => 'Arial',
                        'bold'      => true,
                        'italic'    => false
                    )
                )
            );
            $spreadsheet->getActiveSheet()->getColumnDimension("$k")->setAutoSize(true);
        }

        foreach ($data['list'] as $k => $item) {
            $i++;

            $nome = $item->user->name;
            $spreadsheet->getActiveSheet()->SetCellValue("A$i", $nome);
            $spreadsheet->getActiveSheet()->SetCellValue("B$i", $item->type);
            $spreadsheet->getActiveSheet()->SetCellValue("C$i", dataOra($item->start_at));
            $spreadsheet->getActiveSheet()->SetCellValue("D$i", dataOra($item->end_at));
            $spreadsheet->getActiveSheet()->SetCellValue("E$i", $item->note_office);

        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));
        return redirect()->to(url('temp/'.$filename));
    }
}
