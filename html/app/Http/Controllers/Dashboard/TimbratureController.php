<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\TimbraturaDeleted;
use App\Events\Illuminate\Events\TimbraturaModified;
use App\Exceptions\TimbraturaException;
use App\Http\Controllers\Controller;
use App\Models\Commessa;
use App\Models\Item;
use App\Models\Timbratura;
use App\Models\TimbraturaPermesso;
use App\Models\User;
use Carbon\CarbonPeriod;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TimbratureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('can-list-timbrature-module')) {
            abort(401);
        }

        $data['date'] = $request->input('dateTimbrature', \Carbon\Carbon::yesterday()->toDateString());


        $timbrature = Timbratura::whereDate('marked_at',  $data['date'])
            ->orderBy('users_id')
            ->orderBy('marked_at')
            ->with('user')
            ->get();

        $data['list'] = $timbrature->groupBy(
            function($item) {
                return $item->users_id.'#'.$item->user->name;
            }
        );

        if ($request->input('export', null)) {
            return $this->export($data);
        }
        // dump($data['list']);

        return view('dashboard.timbrature.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Gate::allows('is-user-timbrature-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Rilevazione presenze consente di timbrare le entrate e le uscite
            degli utenti geolocalizzando il punto in cui le stesse sono state effettuate.<br>
            È possibile estrapolare i dati di timbratura, normalizzare le anomalie ed esportare le liste filtrate per giornata in excel';
            return view('layouts.helpers.module-deactive', $data);
        }

        if ($request->has('_admin')) {
            if (!Gate::allows('can-list-timbrature-module')) {
                abort(401);
            }
            else {
                $data['admin'] = true;
                return view('dashboard.timbrature.create-power-user', $data);
            }
        }

        $data['timbrature'] = DB::table('timbrature')
            ->where('users_id', \auth()->user()->id)
            ->whereDate('marked_at', \Carbon\Carbon::today()->toDateString())
            ->orderby('marked_at')
            ->get();

        /***
         * verifico se l'utente è associato a qualche lavorazione
         * di commessa
        **/
        $data['commesse'] = collect([]);
        /** Disabilito l'associazione tra timbrature e commesse
        $id_utente = getUtenteIdBySessionUser();
        if ($id_utente) {
            $data['commesse'] = Commessa::where('item_id', $id_utente)
                ->where('data_inizio_prevista', '<=', \Carbon\Carbon::now()->toDateString())
                ->where('data_fine_prevista', '>=', \Carbon\Carbon::now()->toDateString())
                ->with('root')
                ->get();
        }
        **/

        return view('dashboard.timbrature.create', $data);
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
        ];

        if ($request->has('_users_id')) {
            $validationRules['_time'] = 'required';
        }

        if ($request->has('_amin')) {
            $validationRules['_users_id'] = 'required';
            $validationRules['_marked_at'] = 'required';
            $validationRules['_time'] = 'required';
        }

        $validatedData = $request->validate($validationRules);

        $el = new Timbratura();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_item_id', '_users_id', '_marked_at', '_time', '_admin', 'commesse_id']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {

            $el->users_id = \auth()->user()->id;
            $el->azienda_id = getAziendaId();
            $el->marked_at = \Carbon\Carbon::now();

            if ($request->has('commesse_id')) {
                $commessa = Commessa::with('root')->find($request->input('commesse_id'));
                if (!$commessa) {
                    throw new \Exception('Commessa non trovata');
                }

                $el->commesse_id = $commessa->id;
                $el->commesse_label = Str::title($commessa->root->label.' / '.$commessa->parent->label);
            }

            $action = route('timbrature.create');

            if ($request->has('_users_id')) {

                $_marked_at = $request->input('_marked_at');

                if ($request->has('_admin')) {
                    // $_marked_at = strToDate($_marked_at)->toDateString();
                }

                $u = User::where('id', $request->input('_users_id'))
                    ->firstOrFail();

                if ($u->utente_id) {
                    $utente = Item::where('id', $u->utente_id)->firstOrFail();
                }

                $el->users_id = $u->id;
                $el->marked_at = $_marked_at.' '.$request->input('_time').':00';
                $el->updated_by = auth()->user()->id;

                $action = route('timbrature.edit', [$request->input('_users_id'), 'date' => $_marked_at]);
            }
            $el->save();

            if (Gate::allows('is-commesse-module-enabled')) {
                // evento
                event(new TimbraturaModified($el));
            }

            DB::commit();


            return redirect()->to($action)->with('success', 'Timbratura inserita!');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if (!Gate::allows('can-list-timbrature-module')) {
            abort(401);
        }

        $data['user'] = User::where('id', $id)->firstOrFail();
        if ($data['user']->utente_id) {
            $utente = Item::where('id', $data['user']->utente_id)->firstOrFail();
        }

        $data['date'] = data($request->input('date'));

        $data['timbrature'] = Timbratura::where('users_id', $id)
            ->whereDate('marked_at', $request->input('date'))
            ->orderby('marked_at')
            ->with('user')
            ->get();

        return view('dashboard.timbrature.edit', $data);

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
    public function destroy($id, Request $request)
    {
        $el = Timbratura::find($id);
        if (!$el) abort('404');


        DB::beginTransaction();
        try {

            $el->delete();

            if (Gate::allows('is-commesse-module-enabled')) {
                // evento
                event(new TimbraturaDeleted($el));
            }

            DB::commit();
            return basicSaveResponse($request, false);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return basicSaveResponse($request, true, $e->getMessage());
        }
    }

    public function export($data) {
        $filename = uniqid().'-Export-timbrature-'.date('d-m-y').'.xlsx';

        $azienda = getAziendaBySessionUser();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata - " . $azienda->label);
        $spreadsheet->getProperties()->setTitle("Export timbrature " . data($data['date']));
        $spreadsheet->getProperties()->setSubject("Export timbrature " . data($data['date']));
        $spreadsheet->getProperties()->setDescription("Timbrature giornata");

        // indice generico
        $i=1;

        // descrivo i criteri selezionati
        $spreadsheet->getActiveSheet()->SetCellValue("A$i", "Azienda: " . $azienda->label . " Timbrature del: " . data($data['date']));

        $i++;

        $celle = array(
            "A"=>"Utente",
            "B"=>"",
            "C"=>"",
            "D"=>"",
            "E"=>"",
            "F"=>"",
            "G"=>"",
            "H"=>"",
            "I"=>"",
            "L"=>"",
            "M"=>"",
            "N"=>"",
            "O"=>"",
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

        foreach ($data['list'] as $k => $items) {
            $i++;

            $nome = explode('#', $k);
            $spreadsheet->getActiveSheet()->SetCellValue("A$i", $nome[1]);

            $letter = 1;
            foreach ($items as $timbratura) {
                $letter++;
                $letterStr = strtoupper(base_convert($letter + 9, 10, 36));
                $spreadsheet
                    ->getActiveSheet()
                    ->SetCellValue($letterStr.$i, ora($timbratura->marked_at));

                $typeColor = $timbratura->type == 'in' ? '00B542' : 'FF0000';
                $spreadsheet->getActiveSheet()->getStyle($letterStr.$i)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB($typeColor);

                $spreadsheet->getActiveSheet()->getStyle($letterStr.$i)
                    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));
        return redirect()->to(url('temp/'.$filename));
    }

    public function exportMensili($data) {
        $filename = uniqid().'-Export-timbrature-mensili-'.date('d-m-y').'.xlsx';

        $azienda = getAziendaBySessionUser();

        $d = new \Carbon\Carbon($data['date'].'-01');

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata - " . $azienda->label);
        $spreadsheet->getProperties()->setTitle("Export timbrature mensili" . $d->format('m-Y'));
        $spreadsheet->getProperties()->setSubject("Export timbrature mensili" . $d->format('m-Y'));
        $spreadsheet->getProperties()->setDescription("Timbrature mensili");

        // indice generico
        $i=1;

        // descrivo i criteri selezionati
        $spreadsheet->getActiveSheet()->SetCellValue("A$i", "Azienda: " . $azienda->label . " Timbrature del: " . $d->format('m-Y'));

        $i++;

        $celle = ["Utente / giorno"];
        foreach ($data['period'] as $p) {
            $celle[] = $p->format('d');
        }

        $i++;
        foreach ($celle as $k => $v){
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($k + 1, $i, $v);
            $spreadsheet->getActiveSheet()->getStyleByColumnAndRow($k + 1, $i)->applyFromArray(
                array(
                    'font'    => array(
                        'name'      => 'Arial',
                        'bold'      => true,
                        'italic'    => false
                    )
                )
            );
            $spreadsheet->getActiveSheet()->getColumnDimensionByColumn($k+ 1)->setAutoSize(true);
        }

        foreach ($data['users'] as $users_id => $name) {
            $i++;
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, $i, Str::title($name));

            $k = 1;
            foreach($data['period'] as $p) {
                $k++;

                $d = $p->toDateString();
                $result = @$data['listChecked'][$users_id][$d];

                if (isset($result)) {
                    if(is_numeric($result)) {
                        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($k, $i, $result);
                    }
                }
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));
        return redirect()->to(url('temp/'.$filename));
    }

    public function refreshSelectCommesse(Request $request) {
        $user = User::find($request->input('user_id'));
        if (!$user) abort(404);

        $data['utente_id'] = $user->utente_id;
        $data['date'] = $request->input('date');

        return view('dashboard.timbrature.components.refresh-select-commesse', $data);
    }

    public function mensili(Request $request)
    {
        if (!Gate::allows('can-list-timbrature-module')) {
            abort(401);
        }

        $data['date'] = $request->input('dateTimbratureMese', \Carbon\Carbon::now()->format('Y-m'));
        $d = new \Carbon\Carbon($data['date'].'-01');

        $data['date'] = $d->format('Y-m');

        // dump($d->startOfMonth()->startOfDay()->toDateTimeString());
        // dd($d->endOfMonth()->endOfDay()->toDateTimeString());

        $timbrature = Timbratura::whereBetween('marked_at',  [$d->startOfMonth()->startOfDay()->toDateTimeString(), $d->endOfMonth()->endOfDay()->toDateTimeString()])
            ->orderBy('users_id')
            ->orderBy('marked_at')
            ->with('user')
            ->get();

        // dump($timbrature);
        $data['users'] = $timbrature->reduce(function ($reducer, $item) {
            // Log::info($item->users_id.' '.$item->user->name);
            $reducer[$item->users_id] = $item->user->name;
            return $reducer;
        }, []);


        $permessi = TimbraturaPermesso::where('status', 'accettato')
            ->where(function ($query) use ($d) {
                $query->whereBetween('start_at',  [$d->startOfMonth()->startOfDay()->toDateTimeString(), $d->endOfMonth()->endOfDay()->toDateTimeString()])
                    ->orWhereBetween('start_at',  [$d->startOfMonth()->startOfDay()->toDateTimeString(), $d->endOfMonth()->endOfDay()->toDateTimeString()]);
            })
            ->orderBy('users_id')
            ->orderBy('start_at')
            ->with('user')
            ->get();

        // dump($data['users']);
        $data['usersPermessi'] = $permessi->reduce(function ($reducer, $item) {
            // Log::info($item->users_id.' '.$item->user->name);
            $reducer[$item->users_id] = $item->user->name;
            return $reducer;
        }, []);

        // union $data['users'] e $data['usersPermessi'] preserve array keys
        $data['users'] = $data['users'] + $data['usersPermessi'];
        // dd($data['users']);


        $period = \Carbon\CarbonPeriod::create($d->startOfMonth()->toDateString(), $d->endOfMonth()->toDateString());
        $data['period'] = $period->toArray();
        // dump($data['period']);

        $data['list'] = [];
        foreach ($timbrature as $t) {
            $d = new \Carbon\Carbon($t->marked_at);
            $data['list'][$t->users_id][$d->toDateString()][] = $t;
        }

        $data['listChecked'] = [];
        $data['list'] = collect($data['list']);
        foreach ($data['list'] as $users_id => $dates) {
            foreach ($dates as $d => $timbratureGiornata) {
                // $data['list'][$users_id][$d] = $this->getDayTimbratureInfo(collect($timbratureGiornata));
                $data['listChecked'][$users_id][$d] = $this->getDayTimbratureInfo(collect($timbratureGiornata));
            }
        }

        $data['listPermessi'] = [];
        foreach ($permessi as $t) {
            $type = $t->type;
            switch ($type) {
                case 'ferie':
                    $d = new \Carbon\Carbon($t->start_at);
                    $e = new \Carbon\Carbon($t->end_at);

                    $period = CarbonPeriod::create($d->toDateString(), $e->toDateString());
                    // Iterate over the period
                    foreach ($period as $date) {
                        $data['listPermessi'][$t->users_id][$date->format('Y-m-d')] = $type;
                    }
                    break;
                default:
                    $d = new \Carbon\Carbon($t->start_at);
                    $data['listPermessi'][$t->users_id][$d->toDateString()] = $type;
            }
        }

        // dd($data['listPermessi']);

        // dd($data['listChecked']);

        if ($request->input('export', null)) {
            return $this->exportMensili($data);
        }
        // dump($data['list']);

        return view('dashboard.timbrature.mensili', $data);
    }

    private function getDayTimbratureInfo($collectionTimbratureGiornataUser) {
        try {

            $type = 'in';
            foreach ($collectionTimbratureGiornataUser as $_t) {
                if ($_t->type !== $type) {
                    // squadrato
                    throw new TimbraturaException('Squadratura per ordine timbrature sbagliato');
                }
                $type = $type === 'in' ? 'out' : 'in';
            }

            $ins =  $collectionTimbratureGiornataUser->filter(function($item){
                return $item->type === 'in';
            });

            $outs =  $collectionTimbratureGiornataUser->filter(function($item){
                return $item->type !== 'in';
            });

            if (!$ins->count() || !$outs->count()) {
                // squadrato
                throw new TimbraturaException('Squadratura su conteggio ingressi / uscite');
            }

            if ($ins->count() !=  $outs->count()) {
                // squadrato
                throw new TimbraturaException('Squadratura su conteggio ingressi / uscite');
            }

            $ins = $ins->values();
            $outs = $outs->values();

            $minutiLavorati = 0;
            foreach ($ins as $index => $in) {
                $out = $outs[$index];
                $di = new \Carbon\Carbon($in->marked_at);
                $do = new \Carbon\Carbon($out->marked_at);

                if ($do->lt($di)) {
                    // squadrato
                    throw new TimbraturaException('Squadratura per uscita minore di entrata');
                }

                $minutiLavorati = $minutiLavorati + abs($do->diffInMinutes($di));
            }

            /** eseguire calcolo ore totali lavorate **/
            $oreLavorate = $minutiLavorati / 60;
            return number_format((float)$oreLavorate, 2, '.', '');

        }
        catch (TimbraturaException $e) {
            return $e->getMessage();
        }
    }

    public function qrGenerator() {
        $generate = route('timbrature.create');
        $writer = new PngWriter();
        $qrCode = QrCode::create($generate)
            ->setSize(500)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $contents = $writer->write($qrCode)->getString();

        $filename = time().'_'.Auth::user()->id.'.png';
        $path = public_path('export/qr/'.$filename);
        file_put_contents($path, $contents);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
