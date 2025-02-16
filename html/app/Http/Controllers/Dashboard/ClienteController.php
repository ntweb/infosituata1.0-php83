<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Cliente::orderBy('created_at', 'desc');

        if($request->has('q')) {
            $query->where(function ($query) use ($request) {
                $query->where('rs', 'like', '%'.$request->get('q').'%')
                    ->orWhere('cognome', 'like', '%'.$request->get('q').'%')
                    ->orWhere('nome', 'like', '%'.$request->get('q').'%')
                    ->orWhere('piva', 'like', '%'.$request->get('q').'%')
                    ->orWhere('cf', 'like', '%'.$request->get('q').'%')
                    ->orWhere('sdi', 'like', '%'.$request->get('q').'%')
                    ->orWhere('pec', 'like', '%'.$request->get('q').'%');
            });

            $data['list'] = $query->paginate(500);
            return view('dashboard.cliente.tables.index', $data);
        }

        if($request->has('_render_table')) {
            $data['list'] = $query->paginate(500);
            return view('dashboard.cliente.tables.index', $data);
        }

        $data['list'] = $query->paginate(500);
        return view('dashboard.cliente.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        return view('dashboard.cliente.modals.clienti-edit', $data);
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
            'rs' => 'required',
            'cognome' => 'required',
            'nome' => 'required',
        ];

        if ($request->has('fl_persona_fisica')) {
            unset($validationRules['rs']);
        }
        else {
            unset($validationRules['cognome']);
            unset($validationRules['nome']);
        }

        $validatedData = $request->validate($validationRules);
        $el = new Cliente;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'fl_addebito_bollo', 'fl_split_payment', 'fl_ente_pubblico', 'fl_soggetto_privato', 'fl_persona_fisica']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }


        try {
            $azienda_id = getAziendaId();
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;

            $el->fl_addebito_bollo = $request->has('fl_addebito_bollo') ? '1' : '0';
            $el->fl_split_payment = $request->has('fl_split_payment') ? '1' : '0';
            $el->fl_ente_pubblico = $request->has('fl_ente_pubblico') ? '1' : '0';
            $el->fl_soggetto_privato = $request->has('fl_soggetto_privato') ? '1' : '0';
            $el->fl_persona_fisica = $request->has('fl_persona_fisica') ? '1' : '0';

            $el->id = Str::uuid();

            $el->save();

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
        $data['el'] = Cliente::find($id);
        if (!$data['el']) abort(404);

        return view('dashboard.cliente.modals.clienti-edit', $data);
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
        $el = Cliente::find($id);
        if (!$el) abort(404);

        $validationRules = [
            'rs' => 'required',
            'cognome' => 'required',
            'nome' => 'required',
        ];

        if ($request->has('fl_persona_fisica')) {
            unset($validationRules['rs']);
        }
        else {
            unset($validationRules['cognome']);
            unset($validationRules['nome']);
        }

        $validatedData = $request->validate($validationRules);
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'fl_addebito_bollo', 'fl_split_payment', 'fl_ente_pubblico', 'fl_soggetto_privato', 'fl_persona_fisica']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }


        try {

            $el->fl_addebito_bollo = $request->has('fl_addebito_bollo') ? '1' : '0';
            $el->fl_split_payment = $request->has('fl_split_payment') ? '1' : '0';
            $el->fl_ente_pubblico = $request->has('fl_ente_pubblico') ? '1' : '0';
            $el->fl_soggetto_privato = $request->has('fl_soggetto_privato') ? '1' : '0';
            $el->fl_persona_fisica = $request->has('fl_persona_fisica') ? '1' : '0';

            $el->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
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
        //
    }

    public function select2(Request $request)
    {
        $t = $request->input('term', null);
        $list = [];


        if (trim($t) != '') {

            $list = Cliente::where(function($query) use ($t){
                    $query->where('rs', 'like', '%'.$t.'%')
                        ->orWhere('cognome', 'like', '%'.$t.'%')
                        ->orWhere('nome', 'like', '%'.$t.'%')
                        ->orWhere('piva', 'like', '%'.$t.'%')
                        ->orWhere('cf', 'like', '%'.$t.'%');
                })->get();
        }

        if (count($list)) {
            $list = $list->map(function ($item){
                return ['id' => $item->id, 'text' => $item->rs ?? $item->cognome . ' ' . $item->nome];
            });
        }

        $data['results'] = $list;
        return response()->json($data);
    }

    public function import() {

        /** Verifico se c'è già un file da importare **/
        $aziendaId = getAziendaId();
        $exist = DB::table('clienti_import_file')->where('azienda_id', $aziendaId)->first();

        $toDoImport = DB::table('clienti_import')->where('azienda_id', $aziendaId)->orderBy('rs')->orderBy('cognome')->orderBy('nome')->get();

        $data['exist'] = $exist;
        $data['toDoImport'] = $toDoImport;
        return view('dashboard.cliente.import', $data);
    }

    public function upload(Request $request) {
        $validationRules = [
            'attachment' => 'required|file|max:25000|mimes:xlx,xlsx'
        ];

        $validatedData = $request->validate($validationRules);

        $aziendaId = getAziendaId();
        $path = 'import-clienti/'.$aziendaId.'/';

        foreach ($request->file() as $type => $files) {
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $filename = pathinfo($filename, PATHINFO_FILENAME);
                $filename = Str::slug($filename);
                $extension = $file->getClientOriginalExtension();
                $filename = $filename . '.' . strtolower($extension);

                Storage::disk('public')->put($path.'/'.$filename, $file->get());

                $now = \Carbon\Carbon::now();
                DB::table('clienti_import_file')->insert([
                    'azienda_id' => $aziendaId,
                    'filename' => $filename,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }

        return response()->redirectTo(route('cliente.import'));
    }

    public function importCancel() {
        $aziendaId = getAziendaId();
        $path = 'import-clienti/'.$aziendaId.'/';
        $filename = 'import.xls';

        DB::table('clienti_import_file')->where('azienda_id', $aziendaId)->delete();
        DB::table('clienti_import')->where('azienda_id', $aziendaId)->delete();

        Storage::disk('public')->delete($path.'/'.$filename);

        return response()->redirectTo(route('cliente.import'));
    }

    public function doImport() {
        $aziendaId = getAziendaId();
        $toDoImport = DB::table('clienti_import')->where('azienda_id', $aziendaId)->get();

        if ($toDoImport->count()) {
            foreach ($toDoImport as $cl) {
                if ($cl->tipo_operazione !== 'error') {
                    $c = new Cliente;
                    $c->id = Str::uuid();

                    if ($cl->clienti_id_update) {
                        $c = Cliente::find($cl->clienti_id_update);
                    }

                    $c->azienda_id = $cl->azienda_id;
                    $c->rs = $cl->rs;
                    $c->nome = $cl->nome;
                    $c->cognome = $cl->cognome;
                    $c->piva = $cl->piva;
                    $c->cf = $cl->cf;
                    $c->indirizzo = $cl->indirizzo;
                    $c->cap = $cl->cap;
                    $c->citta = $cl->citta;
                    $c->provincia = $cl->provincia;
                    $c->telefono = $cl->telefono;
                    $c->sdi = $cl->sdi;
                    $c->pec = $cl->pec;

                    $c->save();
                }
            }
        }

        DB::table('clienti_import')->where('azienda_id', $aziendaId)->delete();
        return response()->redirectTo(route('cliente.index'));
    }

    public function export() {
        $filename = 'Export-clienti-'.auth()->user()->id.time().'.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata.com");
        $spreadsheet->getProperties()->setTitle("Export lista clienti");
        $spreadsheet->getProperties()->setSubject("Export lista clienti");
        $spreadsheet->getProperties()->setDescription("Export lista clienti");

        $i=1;
        //descrivo i criteri selezionati
        // $spreadsheet->getActiveSheet()->SetCellValue("A$i", "ITEM: " . Str::title($el->extras1));

        $celle = array(
            "A"=>"Ragione sociale",
            "B"=>"Nome",
            "C"=>"Cognome",
            "D"=>"PIVA",
            "E"=>"CF",
            "F"=> "Indirizzo",
            "G"=> "Città",
            "H"=> "Provincia",
            "I"=> "Telefono",
            "J"=> "Codice SDI",
            "K"=> "PEC",
        );

        foreach ($celle as $k=>$v){
            //scrivo l'intestazione della colonna
            $spreadsheet->getActiveSheet()->SetCellValue("$k$i", $v);

            //formatto le intestazioni delle colonne
            $spreadsheet->getActiveSheet()->getStyle("$k$i")->applyFromArray(
                array(
                    'font'    => array(
                        'name'      => 'Arial',
                        'bold'      => true,
                        'italic'    => false
                    )
                )
            );

            //imposto per tutte le colonne l'autosize
            if ($k!='A') {
                $spreadsheet->getActiveSheet()->getColumnDimension("$k")->setAutoSize(true);
            }
        }
        $i++;
        $spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth(20);


        /**
         * query lista
         **/
        $list = Cliente::orderBy('rs')->orderBy('cognome')->orderBy('nome')->get();


        $styleAlignLeftString = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ]
        ];

        foreach ($list as $l)
        {
            $cell = $spreadsheet->getActiveSheet()->getCell("A$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->rs), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("B$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->nome), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("C$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->cognome), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("D$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->piva), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("E$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->cf), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("F$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->indirizzo), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("G$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->cap), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("H$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->citta), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("I$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->provincia), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("J$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->telefono), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("K$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->sdi), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("L$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->pec), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));

        return response()->download(public_path('temp/'.$filename));
    }
}
