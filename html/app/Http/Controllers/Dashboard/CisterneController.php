<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attrezzatura;
use App\Models\Carburante;
use App\Models\Cisterna;
use App\Models\CisternaLog;
use App\Models\Gruppo;
use App\Models\Manutenzione;
use App\Models\Mezzo;
use App\Models\Utente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CisterneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['list'] = Cisterna::all();
        return view('dashboard.cisterne.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('can_create_mezzi'))
            abort(403);

        $gruppiIds = Gruppo::get()->pluck('id', 'id');
        $data['gruppi'] = Gruppo::whereIn('id', $gruppiIds)->select('id', 'label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        return view('dashboard.cisterne.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validationRules = [
            'label' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new Cisterna();
        $el->azienda_id = getAziendaId();

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'gruppi_ids':
                    $el->$k = json_encode($v);
                    break;
                default:
                    $el->$k = $v;
            }
        }

        try {
            $el->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);

            return redirect()->route('cisterne.index', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }
        catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $el = Cisterna::find($id);
        if (!$el) {
            abort(404);
        }

        $data['el'] = $el;
        $data['gruppiSel'] = json_decode($el->gruppi_ids);
        // make value equal at key
        $data['gruppiSel'] = array_combine($data['gruppiSel'], $data['gruppiSel']);

        $gruppiIds = Gruppo::get()->pluck('id', 'id');
        $data['gruppi'] = Gruppo::whereIn('id', $gruppiIds)->select('id', 'label')->get()->pluck('label', 'id');

        $data['carichi'] = CisternaLog::where('cisterne_id', $el->id)
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();

        $data['schedeCarburante'] = Carburante::where('cisterne_id', $id)
            ->with('item')
            ->orderBy('data', 'desc')
            ->limit(50)
            ->get();

        return view('dashboard.cisterne.create', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $el = Cisterna::find($id);
        if (!$el) {
            abort(404);
        }

        $validationRules = [
            'label' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'gruppi_ids':
                    $el->$k = json_encode($v);
                    break;
                default:
                    $el->$k = $v;
            }
        }

        try {
            $el->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);

            return redirect()->route('cisterne.index', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }
        catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function carico(Request $request, $id) {
        $validationRules = [
            'litri' => 'required|numeric|min:0.01',
            'prezzo' => 'required|numeric|min:0.01',
        ];

        $validatedData = $request->validate($validationRules);

        $cisterna = Cisterna::find($id);
        if (!$cisterna) {
            abort(404);
        }

        $el = new CisternaLog();
        $el->cisterne_id = $id;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {
            $cisterna->livello_attuale = $cisterna->livello_attuale + $el->litri;
            $cisterna->save();

            $el->save();

            DB::commit();
            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }

    }

    public function caricoDestroy($cisterne_id, $id) {
        $cisterna = Cisterna::find($cisterne_id);
        if (!$cisterna) {
            abort(404);
        }

        $cisternaLog = CisternaLog::find($id);
        if (!$cisternaLog) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $cisterna->livello_attuale = $cisterna->livello_attuale - $cisternaLog->litri;
            $cisterna->save();

            $cisternaLog->delete();

            DB::commit();
            $payload = 'Cancellazione avvenuta correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di cancellazione!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function caricoExport($id) {
        $filename = 'Export-carichi-'.auth()->user()->id.time().'.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata.com");
        $spreadsheet->getProperties()->setTitle("Export carichi cisterna");
        $spreadsheet->getProperties()->setSubject("Export carichi cisterna");
        $spreadsheet->getProperties()->setDescription("Export carichi cisterna");

        $i=1;
        //descrivo i criteri selezionati
        // $spreadsheet->getActiveSheet()->SetCellValue("A$i", "ITEM: " . Str::title($el->extras1));
        $i++;

        $celle = array(
            "A"=>"Cisterna",
            "B"=>"Carico/Litri",
            "C"=> "Prezzo/Litro",
            "D"=> "Data carico"
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
        $list = CisternaLog::where('cisterne_id', $id)
            ->orderBy('id', 'desc')
            ->limit(100)
            ->with('cisterna')
            ->get();


        $styleAlignLeftString = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ]
        ];

        foreach ($list as $l)
        {
            /** Etichetta **/
            $cell = $spreadsheet->getActiveSheet()->getCell("A$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->cisterna->label), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            /** Litri **/
            $cell = $spreadsheet->getActiveSheet()->getCell("B$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(strtolower($l->litri), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);


            /** Litri **/
            $cell = $spreadsheet->getActiveSheet()->getCell("C$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(euro($l->prezzo), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            /** Litri **/
            $cell = $spreadsheet->getActiveSheet()->getCell("D$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(data($l->created_at), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);


            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));

        return response()->download(public_path('temp/'.$filename));
    }
}
