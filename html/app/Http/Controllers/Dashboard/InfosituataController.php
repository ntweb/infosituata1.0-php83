<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\AttachmentS3;
use App\Models\Attrezzatura;
use App\Models\Checklist;
use App\Models\InfosituataModule;
use App\Models\Item;
use App\Models\Materiale;
use App\Models\Mezzo;
use App\Models\Rapportino;
use App\Models\Risorsa;
use App\Models\RisorsaLog;
use App\Models\Utente;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InfosituataController extends Controller
{

    public function check(Request $request, $md5_id) {

        $item = Item::whereRaw("md5(id) = '$md5_id'")->first();
        if (!$item) abort(404);

        switch ($item->controller) {
            case 'utente':
                risorsaLog($item);
                return $this->_utente($md5_id);
                break;

            case 'attrezzatura':
                risorsaLog($item);
                return $this->_attrezzatura($md5_id);
                break;

            case 'materiale':
                risorsaLog($item);
                return $this->_materiale($md5_id);
                break;

            case 'mezzo':
                risorsaLog($item);
                return $this->_mezzo($md5_id);
                break;

            case 'risorsa':
                if ($request->has('check_scadenza'))
                    return $this->_risorsa($md5_id);

                return redirect()->route('risorse.show', [$item->id]);
                break;
        }

        abort(404);
    }

    public function qr(Request $request, $format) {
        $generate = urldecode($request->get('generate'));

        $writer = new PngWriter();
        $qrCode = QrCode::create($generate)
            ->setSize(500)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        switch ($format) {
            case 'png':
                $contents = $writer->write($qrCode)->getString();
                break;
            default:
                $contents = $writer->write($qrCode)->getString();
        }

        $filename = time().'_'.Auth::user()->id.'.'.$format;
        $path = public_path('export/qr/'.$filename);
        file_put_contents($path, $contents);
        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function log($utente_id) {

        $item = Item::whereId($utente_id)->whereController('utente')->first();
        if (!$item) abort(404);

        $data['el'] = $item;
        $data['logs'] = RisorsaLog::whereUtenteId($utente_id)
                                                    ->orderBy('created_at', 'desc')
                                                    ->with(['risorsa'])
                                                    ->get();

        return view('dashboard.infosituata.utente.log', $data);
    }

    public function export($utente_id) {

        $item = Item::whereId($utente_id)->whereController('utente')->first();
        if (!$item) abort(404);

        $list = RisorsaLog::whereUtenteId($utente_id)
            ->orderBy('created_at', 'desc')
            ->with(['risorsa'])
            ->get();

        $filename = 'Export-log-'.Str::slug($item->label).'-'.time().'.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata.com");
        $spreadsheet->getProperties()->setTitle("Export log utente" . $item->label);
        $spreadsheet->getProperties()->setSubject("Export log utente" . $item->label);
        $spreadsheet->getProperties()->setDescription("Export log utente" . $item->label);

        $i=1;
        //descrivo i criteri selezionati
        $spreadsheet->getActiveSheet()->SetCellValue("A$i", "Utente: " . Str::title($item->label));
        $i++;

        $celle = array(
            "A"=>"Item",
            "B"=>"Tipologia",
            "C"=> "Presa visione",
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


        $styleAlignLeftString = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ]
        ];

        foreach ($list as $l)
        {
            $targa = '';
            if ($l->risorsa->controller == 'mezzo')
                $targa = ' ['.$l->risorsa->extras3.']';

            $cell = $spreadsheet->getActiveSheet()->getCell("A$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->risorsa->label) . $targa, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("B$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(strtolower($l->risorsa->controller), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);


            $spreadsheet->getActiveSheet()->SetCellValue("C$i", dataOra($l->created_at));

            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));

        return response()->download(public_path('temp/'.$filename));

    }

    public function visibility(Request $request, $item_id) {
        // Log::info($request->all());
        $item = Item::whereId($item_id)->first();
        if (!$item) abort(404);

        $item->visibility = $request->get('visibility');
        $item->visibility_gruppi_id = $request->input('visibility_gruppi_id', null);
        DB::beginTransaction();
        try {
            $item->save();

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

    private function _utente($md5_id) {
        $el = Utente::with(['user'])->whereRaw("md5(id) = '$md5_id'")->first();
        if (!$el) abort(404);

        $data['module'] = InfosituataModule::whereModule('utente')->with('details')->first();
        $data['el'] = $el;

        $data['attachments'] = AttachmentS3::where('reference_id', $el->id)
            ->where('reference_table', 'items')
            ->where('to_delete', '0')
            ->where('is_embedded', '0')
            ->get();

        $data['listChecklist'] = Checklist::where('reference_controller', 'utenti')
            ->where('reference_id', $el->id)
            ->orderBy('id', 'desc')
            ->paginate(50);

        $data['listRapportini'] = Rapportino::where('items_id', $el->id)
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('dashboard.infosituata.utente.info', $data);
    }

    private function _attrezzatura($md5_id) {
        $el = Attrezzatura::whereRaw("md5(id) = '$md5_id'")->first();
        if (!$el) abort(404);

        $data['module'] = InfosituataModule::whereModule('attrezzature')->with('details')->first();
        $data['el'] = $el;

        $data['attachments'] = AttachmentS3::where('reference_id', $el->id)
            ->where('reference_table', 'items')
            ->where('to_delete', '0')
            ->where('is_embedded', '0')
            ->get();

        $data['listChecklist'] = Checklist::where('reference_controller', 'attrezzature')
            ->where('reference_id', $el->id)
            ->orderBy('id', 'desc')
            ->paginate(50);

        $data['listRapportini'] = Rapportino::where('items_id', $el->id)
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('dashboard.infosituata.attrezzatura.info', $data);
    }

    private function _materiale($md5_id) {
        $el = Materiale::whereRaw("md5(id) = '$md5_id'")->first();
        if (!$el) abort(404);

        $data['module'] = InfosituataModule::whereModule('materiale')->with('details')->first();
        $data['el'] = $el;

        $data['attachments'] = AttachmentS3::where('reference_id', $el->id)
            ->where('reference_table', 'items')
            ->where('to_delete', '0')
            ->where('is_embedded', '0')
            ->get();

        $data['listChecklist'] = Checklist::where('reference_controller', 'materiali')
            ->where('reference_id', $el->id)
            ->orderBy('id', 'desc')
            ->paginate(50);

        $data['listRapportini'] = Rapportino::where('items_id', $el->id)
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('dashboard.infosituata.materiale.info', $data);
    }

    private function _mezzo($md5_id) {
        $el = Mezzo::whereRaw("md5(id) = '$md5_id'")->first();
        if (!$el) abort(404);

        $data['module'] = InfosituataModule::whereModule('attrezzature')->with('details')->first();
        $data['el'] = $el;

        $data['attachments'] = AttachmentS3::where('reference_id', $el->id)
            ->where('reference_table', 'items')
            ->where('to_delete', '0')
            ->where('is_embedded', '0')
            ->get();

        $data['listChecklist'] = Checklist::where('reference_controller', 'mezzi')
            ->where('reference_id', $el->id)
            ->orderBy('id', 'desc')
            ->paginate(50);

        $data['listRapportini'] = Rapportino::where('items_id', $el->id)
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('dashboard.infosituata.mezzo.info', $data);
    }

    private function _risorsa($md5_id) {
        $el = Risorsa::whereRaw("md5(id) = '$md5_id'")->first();
        if (!$el) abort(404);

        if (!canAccessRisorsa($el))
            abort(404);

        $data['module'] = InfosituataModule::whereModule('risorse')->with('details')->first();
        $data['el'] = $el;

        $data['attachments'] = AttachmentS3::where('reference_id', $el->id)
            ->where('reference_table', 'items')
            ->where('to_delete', '0')
            ->where('is_embedded', '0')
            ->get();

        $data['listChecklist'] = Checklist::where('reference_controller', 'risorse')
            ->where('reference_id', $el->id)
            ->orderBy('id', 'desc')
            ->paginate(50);

        $data['listRapportini'] = Rapportino::where('items_id', $el->id)
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('dashboard.infosituata.risorsa.info', $data);
    }
}
