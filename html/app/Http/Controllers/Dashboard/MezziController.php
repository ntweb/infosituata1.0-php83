<?php
namespace App\Http\Controllers\Dashboard;

use App\Models\Mezzo;
use App\Models\Sede;
use App\Models\Utente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MezziController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Mezzo::with(['azienda']);
        if (Auth::user()->superadmin && $request->has('azienda'))
            $query->whereAziendaId($request->get('azienda'));

        if($request->has('q')) {
            $query->where(function ($query) use ($request) {
                $query->where('extras1', 'like', '%'.$request->get('q').'%')
                    ->orWhere('extras3', 'like', '%'.$request->get('q').'%');
            });
        }

        $data['list'] = $query->paginate(500)->appends(request()->query());
        return view('dashboard.mezzi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['azienda_id'] = (Auth::user()->superadmin && $request->has('azienda')) ? $request->get('azienda') : null;
        return view('dashboard.mezzi.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
            'extras1' => 'required',
            'azienda_id' => 'required',
        ];

        if (!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        $el = new Utente;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {
            $azienda_id = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('azienda_id') : getAziendaId($el);
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;
            $el->controller = 'mezzo';
            $el->save();

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);

            return redirect()->route('mezzi.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        if(Gate::denies('can_create_mezzi'))
            return redirect()->route('infosituata-public.check', md5($id));

        $el = Mezzo::find($id);
        if (!$el) abort('404');

        $data['sedi'] = Sede::whereAziendaId($el->azienda_id)->orderBy('label')->get()->pluck('label', 'id');
        $data['sediSel'] = $el->sedi->pluck('id', 'id');

        $data['el'] = $el;
        return view('dashboard.mezzi.create', $data);
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
        $el = Mezzo::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module')) {
            case 'bigtext':
                $validationRules = [];
                break;

            default:
                $validationRules = [
                    'extras1' => 'required',
                    'azienda_id' => 'required',
                ];
        }

        if(!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {
            if ($request->has('active'))
                $el->active = $request->input('active');

            $azienda_id = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('azienda_id') : getAziendaId($el);
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;
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
    public function destroy($id)
    {
        //
    }

    public function export() {
        $filename = 'Export-mezzi-'.auth()->user()->id.time().'.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata.com");
        $spreadsheet->getProperties()->setTitle("Export lista mezzi");
        $spreadsheet->getProperties()->setSubject("Export lista mezzi");
        $spreadsheet->getProperties()->setDescription("Export lista mezzi");

        $i=1;
        //descrivo i criteri selezionati
        // $spreadsheet->getActiveSheet()->SetCellValue("A$i", "ITEM: " . Str::title($el->extras1));
        $i++;

        $celle = array(
            "A"=>"Etichetta",
            "B"=>"Targa",
            "C"=> "Attivo",
            "D"=> "Scadenze non gestite (ultimi 6 mesi)",
            "E"=> "Sede"
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
        $list = Mezzo::with(['azienda', 'scadenzeNonGestite', 'sedi'])
            ->orderBy('extras1')
            ->orderBy('extras3')
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
            $cell->setValueExplicit(Str::title($l->extras1), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            /** Targa **/
            $cell = $spreadsheet->getActiveSheet()->getCell("B$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(strtolower($l->extras3), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);


            $spreadsheet->getActiveSheet()->SetCellValue("C$i", $l->active ? 'SI' : 'NO');

            $numScadenzeNonGestite = $l->scadenzeNonGestite->count();
            $cell = $spreadsheet->getActiveSheet()->getCell("D$i");
            $cell->setValueExplicit($numScadenzeNonGestite, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            if ($numScadenzeNonGestite) {
                $cell->getStyle()
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $cell->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
            }

            /** Sede **/
            $sede = $l->sedi ? $l->sedi->pluck('label')->implode(', ') : '-';
            $cell = $spreadsheet->getActiveSheet()->getCell("E$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(strtolower($sede), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);


            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));

        return response()->download(public_path('temp/'.$filename));
    }
}

