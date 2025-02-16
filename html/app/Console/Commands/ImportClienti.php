<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportClienti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clienti:import';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Effettua l\'import dei clienti';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $import = DB::table('clienti_import_file')
            ->orderBy('created_at', 'desc')
            ->whereNull('error')
            ->first();
        if ($import) {
            $aziendaId = $import->azienda_id;
            $filename = Storage::disk('public')->path('import-clienti/'.$aziendaId.'/'.$import->filename);
            try {
                Excel::import(new ClienteImport($aziendaId), $filename);
                DB::table('clienti_import_file')->where('azienda_id', $aziendaId)->delete();
            }
            catch (\Exception $e) {
                DB::table('clienti_import_file')->where('azienda_id', $aziendaId)->update([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

}

class ClienteImport implements ToCollection, WithHeadingRow
{

    public $azienda_id;
    public function __construct($azienda_id)
    {
        $this->azienda_id = $azienda_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            // Log::info($row['ragione_sociale']);
            $tipo_operazione = 'insert';
            $clienti_id_update = 0;
            $exist = null;
            $log = null;

            if (trim($row['piva']) != '') {
                $exist = DB::table('clienti')->where('azienda_id', $this->azienda_id)->where('piva', $row['piva'])->first();
            }

            if (trim($row['cf']) != '' && !$exist) {
                $exist = DB::table('clienti')->where('azienda_id', $this->azienda_id)->where('cf', $row['cf'])->first();
            }

            if (trim($row['piva']) == '' && trim($row['cf']) == '') {
                $tipo_operazione = 'error';
                $log = 'Manca piva e cf';
            }

            if (trim($row['ragione_sociale']) == '' && trim($row['nome']) == '' && trim($row['cognome']) == '') {
                $tipo_operazione = 'error';
                $log = 'Mancano rag. sociale, nome, cognome';
            }

            if ($exist) {
                $clienti_id_update = $exist->id;
                $tipo_operazione = 'update';
            }

            DB::table('clienti_import')->insert([
                'azienda_id'=> $this->azienda_id,
                'clienti_id_update'=> $clienti_id_update,
                'rs'=> $row['ragione_sociale'],
                'nome'=> $row['nome'],
                'cognome'=> $row['cognome'],
                'piva'=> $row['piva'],
                'cf'=> $row['cf'],
                'indirizzo'=> $row['indirizzo'],
                'cap'=> $row['cap'],
                'citta'=> $row['citta'],
                'provincia'=> $row['provincia'],
                'telefono'=> $row['telefono'],
                'sdi'=> $row['codice_sdi'],
                'pec'=> $row['pec'],
                'tipo_operazione'=> $tipo_operazione,
                'log'=> $log,
            ]);
        }


    }
}
