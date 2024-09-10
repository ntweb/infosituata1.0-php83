<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ModuliDetailScadenzeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $_files = [
            1 => 'scadenzario-formazione.txt',
            10 => 'collaudi.txt',
            11 => 'controlli.txt',
            8 => 'revisioni.txt',
            12 => 'rinnovi.txt',
            5 => 'verifiche.txt',
        ];

        set_time_limit(0);

        foreach ($_files as $k => $f) {
            $filename = base_path('public_html/import/'.$f);
            echo $filename;

            $delimiter = ";";
            if (File::exists($filename))
            {
                $file_handle = fopen($filename, "r");
                $countline = 0;
                while (!feof($file_handle)) {
                    $line = trim(fgets($file_handle));
                    if ($countline >= 0 && $line != "") {
                        $l = explode($delimiter, $line);

                        $infosituata_moduli_details_id = $k;
                        $azienda_id = 0;
                        $label = $l[0];

                        $description =
                        $description = trim(@$l[3] != '') ? trim($l[3]) : @trim($l[2]);

                        $mesi = 0;
                        if (is_numeric($l[1])) {
                            if ($k == 1) $mesi = $l[1] * 12;
                            else $mesi = $l[1];
                        }

                        DB::table('infosituata_moduli_details_scadenze')->insert([
                            'infosituata_moduli_details_id' => $infosituata_moduli_details_id,
                            'azienda_id' => $azienda_id,
                            'label' => Str::limit($label, 230),
                            'description' => $description,
                            'mesi' => $mesi
                        ]);

                    }

                    $countline++;
                }

                fclose($file_handle);
            }
        }

    }
}
