<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\AttachmentS3;
use App\Models\Risorsa;
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

class RisorseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Risorsa::with(['azienda']);
        if (Auth::user()->superadmin && $request->has('azienda'))
            $query->whereAziendaId($request->get('azienda'));

        if($request->has('q')) {
            $query->where(function ($query) use ($request) {
                $query->where('extras1', 'like', '%'.$request->get('q').'%')
                    ->orWhere('extras2', 'like', '%'.$request->get('q').'%')
                    ->orWhere('extras3', 'like', '%'.$request->get('q').'%');
            });
        }

        $data['list'] = $query->paginate(500)->appends(request()->query());
        return view('dashboard.infosituata.risorse.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Gate::allows('can_create_risorse'))
            abort(401);

        $data['azienda_id'] = (Auth::user()->superadmin && $request->has('azienda')) ? $request->get('azienda') : null;
        //if (packageError('utente', $data['azienda_id']))
        //    return redirect()->action('Dashboard\PackageController@error')->with(['package-error' => 'Non è consentito creare ulteriori utenti']);

        return view('dashboard.infosituata.risorse.create', $data);
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
            $el->controller = 'risorsa';
            $el->save();

            DB::commit();
            return redirect()->action('Dashboard\RisorseController@edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
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
        $el = Risorsa::whereActive('1')->whereId($id)->first();
        if (!$el) abort(404);

        if (!canAccessRisorsa($el))
            abort(404);

        risorsaLog($el);
        // in questo caso è esterna
        /** Invece di fare il redirect cerco di visualizzarla in un iframe **/
        /**
         * if ($el->extras2) return response()->redirectTo($el->extras2);
         */

        $data['el'] = $el;
        $data['attachments'] = AttachmentS3::where('reference_id', $el->id)
            ->where('reference_table', 'items')
            ->where('to_delete', '0')
            ->where('is_embedded', '0')
            ->get();

        $data['scadenze'] = getScadenze($el);
        return view('dashboard.infosituata.risorse.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('can_create_risorse'))
            return redirect()->action('Dashboard\InfosituataPublicController@check', md5($id));

        $el = Risorsa::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        return view('dashboard.infosituata.risorse.create', $data);
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
        $el = Risorsa::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module')) {
            case 'risorsa_esterna':
                $validationRules = [
                    'extras2' => 'sometimes|nullable|url',
                ];
                break;

            case 'risorsa_interna':
                $validationRules = [
                    'big_extras1' => 'required',
                ];
                break;

            default:
                $validationRules = [
                    'extras1' => 'required',
                    'azienda_id' => 'required',
                ];
        }

        if(!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        // Log::info($request->all());

        $validatedData = $request->validate($validationRules);
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);

        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {

            if (!$request->has('_module'))
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
        $filename = 'Export-risorse-'.auth()->user()->id.time().'.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata.com");
        $spreadsheet->getProperties()->setTitle("Export lista risorse");
        $spreadsheet->getProperties()->setSubject("Export lista risorse");
        $spreadsheet->getProperties()->setDescription("Export lista risorse");

        $i=1;
        //descrivo i criteri selezionati
        // $spreadsheet->getActiveSheet()->SetCellValue("A$i", "ITEM: " . Str::title($el->extras1));
        $i++;

        $celle = array(
            "A"=>"Etichetta",
            "B"=>"Visibilità",
            "C"=> "Attivo",
            "D"=> "Scadenze non gestite (ultimi 6 mesi)"
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
        $list = Risorsa::with(['azienda', 'scadenzeNonGestite'])
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

            /** Visibilità **/
            $cell = $spreadsheet->getActiveSheet()->getCell("B$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(strtolower($l->visibiliti), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);


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


            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));

        return response()->download(public_path('temp/'.$filename));
    }
}
