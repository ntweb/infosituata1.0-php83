<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\AttachmentS3ParentDeleted;
use App\Events\Illuminate\Events\CarburanteAddOrUpdEvent;
use App\Models\Carburante;
use App\Models\Cisterna;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CarburanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $can_enter = Gate::allows('can_create_sc_carburante_mezzi') || Gate::allows('can_create_sc_carburante_attrezzature');

        if (!$can_enter)
            abort(401);

        /** tutte le schede **/
        $data = ['dates' => []];
        if (!$request->has('id')) {
            if ($request->has('dates')) {
                $dates = explode(' - ', $request->input('dates'));
                $data['list'] = Carburante::with('item', 'cisterna')
                    ->whereBetween('data', [strToDate($dates[0])->startOfDay()->toDateTimeString(), strToDate($dates[1])->startOfDay()->toDateTimeString()])
                    ->orderBy('items_id')
                    ->orderBy('data', 'desc')
                    ->get();

                // dump($data['list']);

                $dates['0'] = strToDate($dates[0])->toDateString();
                $dates['1'] = strToDate($dates[1])->toDateString();
                $data['dates'] = $dates;

                if ($request->input('export', null)) {
                    return $this->exportSchede($data['dates'], $data['list']);
                }

            }
            return view('dashboard.carburante.index-export', $data);
        }

        // dump($request->all());
        $data['el'] = Item::with('carburante.cisterna')->find($request->input('id', null));
        if (!$data['el']) abort(404);

        $data['back'] = $request->input('back', null);

        return view('dashboard.carburante.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $can_enter = Gate::allows('can_create_sc_carburante_mezzi') || Gate::allows('can_create_sc_carburante_attrezzature');

        if (!$can_enter)
            abort(401);

        $data['item'] = Item::find($request->input('id', null));
        if (!$data['item']) abort(404);

        $data['cisterne'] = Cisterna::get();

        return view('dashboard.carburante.create', $data);
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

        $lastCarburante = Carburante::where('items_id', $request->input('_item_id'))->orderBy('km', 'desc')->first();
        $minKm = $lastCarburante ? $lastCarburante->km + 1 : 1;
        $minDate = $lastCarburante ? $lastCarburante->data : null;

        $validationRules = [
            'litri' => 'required|min:1',
            'km' => 'required|numeric|min:'.$minKm,
            'costo' => 'required|min:1',
            'data' => 'required|date_format:d/m/Y',
        ];

        if ($data['item']->controller == 'attrezzatura') {
            unset($validationRules['km']);
        }

        if ($minDate) {
            $validationRules['data'] = 'required|date_format:d/m/Y|after_or_equal:'.$minDate;
        }

        $validatedData = $request->validate($validationRules);

        $el = new Carburante;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_item_id']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;

            if($k == 'data')
                $el->$k = \Carbon\Carbon::createFromFormat('d/m/Y', $v);

            if ($k == 'cisterne_id' && intval($v) == 0)
                $el->$k = null;
        }

        DB::beginTransaction();
        try {

            $el->items_id = $data['item']->id;
            $el->type = 'carburante';
            $el->azienda_id = getAziendaId();
            $el->created_by = Auth::user()->id;
            $el->save();

            $evt['cisterne_id'] = $el->cisterne_id;
            $evt['litri'] = $el->litri;
            $evt['old_cisterne_id'] = null;
            $evt['old_litri'] = null;

            event(new CarburanteAddOrUpdEvent($evt));

            DB::commit();

            return redirect()->route('carburante.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
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

        $can_enter = Gate::allows('can_create_sc_carburante_mezzi') || Gate::allows('can_create_sc_carburante_attrezzature');

        if (!$can_enter)
            abort(401);


        $data['el'] = Carburante::with(['item', 'cisterna'])->find($id);
        if (!$data['el']) abort(404);

        $data['item'] = $data['el']->item;
        $data['dettagli'] = $data['el']->dettagli;

        $data['back'] = route('carburante.index', ['id' => $data['item']->id]);

        $data['action'] = route('carburante.update', [$id, '_type' => 'json']);

        $data['nextScheda'] = Carburante::where('km', '>', $data['el']->km)->orderBy('km', 'desc')->first();

        $data['cisterne'] = Cisterna::get();

        return view('dashboard.carburante.edit', $data);
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

        $el = Carburante::find($id);
        if (!$el) abort('404');

        $evt['old_cisterne_id'] = $el->cisterne_id;
        $evt['old_litri'] = $el->litri;

        switch ($request->get('_module', null)) {
            default:
                $validationRules = [
                    'data' => 'required|date_format:d/m/Y',
                ];
        }


        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_item_id']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;

            if($k == 'data') {
                $el->$k = \Carbon\Carbon::createFromFormat('d/m/Y', $v);
            }

            if ($k == 'cisterne_id' && intval($v) == 0) {
                $el->$k = null;
            }
        }

        DB::beginTransaction();
        try {

            $el->save();

            $evt['cisterne_id'] = $el->cisterne_id;
            $evt['litri'] = $el->litri;

            event(new CarburanteAddOrUpdEvent($evt));

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

        $el = Carburante::find($id);
        if (!$el) abort(404);
        DB::beginTransaction();
        try {

            $el->delete();

            /** Deleting s3 attachments **/
            event(new AttachmentS3ParentDeleted($id, 'manutenzioni'));

            DB::commit();

            return redirect()->route('controllo.index', ['id' => $el->items_id]);
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

    public function export($id) {
        $el = Item::with(['carburante', 'carburante.cisterna'])->find($id);
        if (!$el) abort(404);

        $filename = 'Export-schede-carburanti-'.auth()->user()->id.time().'.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata.com");
        $spreadsheet->getProperties()->setTitle("Export schede carburanti " . Str::title($el->extras1));
        $spreadsheet->getProperties()->setSubject("Export schede carburanti " . Str::title($el->extras1));
        $spreadsheet->getProperties()->setDescription("Export schede carburanti " . Str::title($el->extras1));

        $i=1;
        //descrivo i criteri selezionati
        $spreadsheet->getActiveSheet()->SetCellValue("A$i", "ITEM: " . Str::title($el->extras1));
        $i++;

        $celle = array(
            "A"=>"Data rifornimento",
            "B"=>"Eseguito da",
            "C"=>"Km",
            "D"=>"litri",
            "E"=>"Costo",
            "F"=>"Cisterna",
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

        foreach ($el->carburante as $l)
        {
            // $_tot_magazzino = $l->dettagli->sum('magazzino');
            // $_tot_acquistati = $l->dettagli->sum('acquistati');

            $spreadsheet->getActiveSheet()->SetCellValue("A$i", data($l->data));
            $spreadsheet->getActiveSheet()->SetCellValue("B$i", Str::title($l->createdBy->name));
            $spreadsheet->getActiveSheet()->SetCellValue("C$i", $l->km);
            $spreadsheet->getActiveSheet()->SetCellValue("D$i", $l->litri);
            $spreadsheet->getActiveSheet()->SetCellValue("E$i", $l->costo);
            $spreadsheet->getActiveSheet()->SetCellValue("F$i", $l->cisterne_id ? $l->cisterna->label : '');
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));

        return response()->download(public_path('temp/'.$filename));
    }

    public function exportSchede($period, $list) {
        $filename = 'Export-schede-carburanti-'.auth()->user()->id.time().'.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata.com");
        $spreadsheet->getProperties()->setTitle("Export schede carburanti " . " periodo: " . $period[0] . ' / ' . $period[1]);
        $spreadsheet->getProperties()->setSubject("Export schede carburanti " . " periodo: " . $period[0] . ' / ' . $period[1]);
        $spreadsheet->getProperties()->setDescription("Export schede carburanti " . " periodo: " . $period[0] . ' / ' . $period[1]);

        $i=1;
        //descrivo i criteri selezionati
        $spreadsheet->getActiveSheet()->SetCellValue("A$i","Export schede carburanti " . " periodo: " . $period[0] . ' / ' . $period[1]);
        $i++;

        $celle = array(
            "A"=>"Mezzo",
            "B"=>"Data rifornimento",
            "C"=>"Eseguito da",
            "D"=>"Km",
            "E"=>"litri",
            "F"=>"Costo",
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

        foreach ($list as $l)
        {
            // $_tot_magazzino = $l->dettagli->sum('magazzino');
            // $_tot_acquistati = $l->dettagli->sum('acquistati');

            $spreadsheet->getActiveSheet()->SetCellValue("A$i", Str::title($l->item->extras1).' ['.$l->item->extras3.']');
            $spreadsheet->getActiveSheet()->SetCellValue("B$i", data($l->data));
            $spreadsheet->getActiveSheet()->SetCellValue("C$i", Str::title($l->createdBy->name));
            $spreadsheet->getActiveSheet()->SetCellValue("D$i", $l->km);
            $spreadsheet->getActiveSheet()->SetCellValue("E$i", $l->litri);
            $spreadsheet->getActiveSheet()->SetCellValue("F$i", $l->costo);
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));

        return response()->download(public_path('temp/'.$filename));
    }
}
