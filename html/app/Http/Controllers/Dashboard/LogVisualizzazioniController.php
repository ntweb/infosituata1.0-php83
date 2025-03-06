<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\RisorsaLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LogVisualizzazioniController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('_search')) {
            $data['list'] = [];

            if ($request->input('search')) {
                $search  = collect($request->input('search'))->filter(function($value, $key) {
                    return isset($value);
                })->toArray();

                if (count($search)) {

                    $query = RisorsaLog::with(['utente', 'risorsa'])
                        ->orderBy('utente_id')
                        ->orderBy('created_at');

                    foreach ($search as $k => $v) {
                        switch ($k) {
                            case 'start_at':
                            case 'end_at':
                                $st = new \Carbon\Carbon($search['start_at']);
                                $en = new \Carbon\Carbon($search['end_at']);
                                $query = $query->whereBetween('created_at', [$st->startOfDay(), $en->endOfDay()]);
                                break;
                            default:
                                $query = $query->where($k, $v);
                        }
                    }

                    $data['list'] = $query->get();
                    $data['from'] = $st->format('d/m/Y');
                    $data['to'] = $en->format('d/m/Y');
                    $data['filename'] = url($this->export($data['list'], $data['from'], $data['to']));
                }
            }

            return view('dashboard.log-visualizzazioni.tables.index', $data);
        }

        return view('dashboard.log-visualizzazioni.index');
    }

    private function export($list, $from, $to) {
        $filename = 'Export-log-'.getAziendaId().'-'.time().'.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata.com");
        $spreadsheet->getProperties()->setTitle("Export log visualizzazioni");
        $spreadsheet->getProperties()->setSubject("Export log visualizzazioni");
        $spreadsheet->getProperties()->setDescription("Export log visualizzazioni");

        $i=1;
        //descrivo i criteri selezionati
        $spreadsheet->getActiveSheet()->SetCellValue("A$i", "Periodo: " . $from . ' - ' . $to);
        $i++;

        $celle = array(
            "A"=>"Utente",
            "B"=>"Visualizzazione",
            "C"=>"",
            "D"=> "Effettuata il",
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
            $cell = $spreadsheet->getActiveSheet()->getCell("A$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->utente->label), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("B$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->risorsa->label), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $cell = $spreadsheet->getActiveSheet()->getCell("C$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->risorsa->controller), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);


            $spreadsheet->getActiveSheet()->SetCellValue("D$i", dataOra($l->created_at));

            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));

        return 'temp/'.$filename;
    }
}
