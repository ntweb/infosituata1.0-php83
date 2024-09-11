<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\AttachmentS3ParentDeleted;
use App\Models\Item;
use App\Models\Manutenzione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class ManutenzioneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // dump($request->all());
        $data['el'] = Item::with('manutenzioni')->find($request->input('id', null));
        if (!$data['el']) abort(404);

        switch ($data['el']->controller) {
            case 'attrezzatura':
                if (!Gate::allows('can_create_manutenzione_attrezzature'))
                    abort(401);

                break;
            default:
                if (!Gate::allows('can_create_manutenzione_mezzi'))
                    abort(401);
        }

        $data['back'] = $request->input('back', null);
        return view('dashboard.manutenzione.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['item'] = Item::find($request->input('id', null));
        if (!$data['item']) abort(404);

        switch ($data['item']->controller) {
            case 'attrezzatura':
                if (!Gate::allows('can_create_manutenzione_attrezzature'))
                    abort(401);

                break;
            default:
                if (!Gate::allows('can_create_manutenzione_mezzi'))
                    abort(401);
        }

        return view('dashboard.manutenzione.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data['item'] = Item::find($request->input('_item_id', null));
        if (!$data['item']) abort(404);

        $validationRules = [
            'costo' => 'required',
            'data' => 'required|date_format:d/m/Y',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new Manutenzione;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_item_id']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;

            if($k == 'data')
                $el->$k = \Carbon\Carbon::createFromFormat('d/m/Y', $v);

        }

        DB::beginTransaction();
        try {

            $el->items_id = $data['item']->id;
            $el->azienda_id = getAziendaId();
            $el->created_by = Auth::user()->id;
            $el->save();

            DB::commit();

            return redirect()->action('Dashboard\ManutenzioneController@edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
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
    public function edit($id)
    {
        $data['el'] = Manutenzione::with(['item', 'dettagli', 'dettagli.ricambio'])->find($id);
        if (!$data['el']) abort(404);

        $data['item'] = $data['el']->item;
        $data['dettagli'] = $data['el']->dettagli;

        switch ($data['item']->controller) {
            case 'attrezzatura':
                if (!Gate::allows('can_create_manutenzione_attrezzature'))
                    abort(401);

                break;
            default:
                if (!Gate::allows('can_create_manutenzione_mezzi'))
                    abort(401);
        }

        $data['back'] = action('Dashboard\ManutenzioneController@index', ['id' => $data['item']->id]);
        $data['action'] = action('Dashboard\ManutenzioneController@update', $id);

        return view('dashboard.manutenzione.edit', $data);
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
        $el = Manutenzione::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module')) {
            default:
                $validationRules = [
                    'data' => 'required|date_format:d/m/Y',
                ];
        }

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_item_id']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;

            if($k == 'data')
                $el->$k = \Carbon\Carbon::createFromFormat('d/m/Y', $v);
        }

        DB::beginTransaction();
        try {

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
    public function destroy(Request $request, $id)
    {
        if(!$request->has('confirm'))
            return redirect()->back()->withInput()->with('error', 'E\' necessario confermare la cancellazione!');

        $el = Manutenzione::find($id);
        if (!$el) abort(404);
        DB::beginTransaction();
        try {
            $el->delete();

            /** Cancellazione dettagli **/
            DB::table('manutenzioni_dettagli')->where('manutenzioni_id', $el->id)->delete();

            /** Deleting s3 attachments **/
            event(new AttachmentS3ParentDeleted($id, 'manutenzioni'));

            DB::commit();

            return redirect()->action('Dashboard\ManutenzioneController@index', ['id' => $el->items_id]);
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

    public function dettagli($id) {
        $manutenzione = Manutenzione::with('dettagli', 'dettagli.ricambio')->find($id);
        $data['dettagli'] = $manutenzione->dettagli;

        return view('dashboard.manutenzione.tables.dettagli', $data);
    }

    public function export($id) {
        $el = Item::with('manutenzioni', 'manutenzioni.dettagli', 'manutenzioni.dettagli.ricambio')->find($id);
        if (!$el) abort(404);

        $filename = 'Export-scheda-manutenzione-'.auth()->user()->id.time().'.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata.com");
        $spreadsheet->getProperties()->setTitle("Export scheda manutenzione " . Str::title($el->extras1));
        $spreadsheet->getProperties()->setSubject("Export scheda manutenzione " . Str::title($el->extras1));
        $spreadsheet->getProperties()->setDescription("Export scheda manutenzione " . Str::title($el->extras1));

        $i=1;
        //descrivo i criteri selezionati
        $spreadsheet->getActiveSheet()->SetCellValue("A$i", "ITEM: " . Str::title($el->extras1));
        $i++;

        $celle = array(
            "A"=>"Data manutenzione",
            "B"=>"Manutentore",
            "C"=>"Tipologia",
            "D"=>"Costo",
            "E"=>"Descrizione",
            "F"=> "Tempo impiegato",
            "G" => "Numero ricambi"
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

        foreach ($el->manutenzioni as $l)
        {
            $_tot_magazzino = $l->dettagli->sum('magazzino');
            $_tot_acquistati = $l->dettagli->sum('acquistati');

            $spreadsheet->getActiveSheet()->SetCellValue("A$i", data($l->data));
            $spreadsheet->getActiveSheet()->SetCellValue("B$i", Str::title($l->esecutore));
            $spreadsheet->getActiveSheet()->SetCellValue("C$i", $l->tipo_1.' / '.$l->tipo_2);
            $spreadsheet->getActiveSheet()->SetCellValue("D$i", $l->costo);
            $spreadsheet->getActiveSheet()->SetCellValue("E$i", Html2Text($l->descrizione));
            $spreadsheet->getActiveSheet()->SetCellValue("F$i", $l->tempo);
            $spreadsheet->getActiveSheet()->SetCellValue("G$i", $_tot_magazzino + $_tot_acquistati);
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));

        return response()->download(public_path('temp/'.$filename));
    }
}
