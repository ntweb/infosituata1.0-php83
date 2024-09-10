<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Hash;
class IvaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $arr = [
            0 => ['codice' => 'E00', 'azienda_id' => null, 'descrizione' => 'escluso', 'descrizione_estesa' => null, 'natura' => 'N1', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            1 => ['codice' => 'E10', 'azienda_id' => null, 'descrizione' => 'es. art. 10', 'descrizione_estesa' => 'Operazione esente 10) 633/72', 'natura' => 'N4', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            2 => ['codice' => 'E15', 'azienda_id' => null, 'descrizione' => 'escl. art. 15', 'descrizione_estesa' => 'Operazione esclusa 15) 633/72', 'natura' => 'N1', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            3 => ['codice' => 'E08,1', 'azienda_id' => null, 'descrizione' => 'es. art. 8c1', 'descrizione_estesa' => 'Operazione nonimponibile 8-c.1a) 633/72', 'natura' => 'N3.1', 'fl_esenzione' => '1', 'fl_spese_bollo' => '0'],
            4 => ['codice' => 'E08,2', 'azienda_id' => null, 'descrizione' => 'es. art. 8c2', 'descrizione_estesa' => null, 'natura' => 'N3', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            5 => ['codice' => 'E26', 'azienda_id' => null, 'descrizione' => 'es. art. 26', 'descrizione_estesa' => null, 'natura' => 'N2', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            6 => ['codice' => 'E10,1', 'azienda_id' => null, 'descrizione' => 'es. art. 10L.A', 'descrizione_estesa' => null, 'natura' => 'N4', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            7 => ['codice' => 'E02', 'azienda_id' => null, 'descrizione' => 'F.C. art. 2', 'descrizione_estesa' => 'Fuori campo articolo 2) DPR 633/72', 'natura' => 'N2.2', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            8 => ['codice' => 'E74', 'azienda_id' => null, 'descrizione' => 'es. art. 74 c1', 'descrizione_estesa' => 'Fuori campo articolo 2) DPR 633/72', 'natura' => 'N2', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            9 => ['codice' => 'F00', 'azienda_id' => null, 'descrizione' => 'Fuor. cam. IVA', 'descrizione_estesa' => 'Fuori campo articolo 2) DPR 633/72', 'natura' => 'N2', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            10 => ['codice' => 'E07', 'azienda_id' => null, 'descrizione' => 'L.17 01/2000', 'descrizione_estesa' => 'Inversione contabile L. 17 633/72', 'natura' => 'N6.3', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            11 => ['codice' => 'E10,4', 'azienda_id' => null, 'descrizione' => 'es. art. 10 c14', 'descrizione_estesa' => null, 'natura' => 'N4', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            12 => ['codice' => 'E04', 'azienda_id' => null, 'descrizione' => 'es. art. 4', 'descrizione_estesa' => null, 'natura' => 'N2', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            13 => ['codice' => 'E72', 'azienda_id' => null, 'descrizione' => 'es. art. 72', 'descrizione_estesa' => null, 'natura' => 'N3', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            14 => ['codice' => 'E38', 'azienda_id' => null, 'descrizione' => 'es. art. 38', 'descrizione_estesa' => null, 'natura' => 'N3', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            15 => ['codice' => 'E17', 'azienda_id' => null, 'descrizione' => 'ar. art. 17', 'descrizione_estesa' => 'Art. 17 comma 6 DPR 633/72', 'natura' => 'N6.3', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            16 => ['codice' => 'E24', 'azienda_id' => null, 'descrizione' => 'Art 1 L.2007', 'descrizione_estesa' => 'Art. 1 comma 100 LEGGE 244/2007', 'natura' => 'N2', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            17 => ['codice' => 'E41', 'azienda_id' => null, 'descrizione' => 'Esent. art. 41', 'descrizione_estesa' => 'Non imponibile 41-c.1) 331/93 Inver-contab', 'natura' => 'N3', 'fl_esenzione' => '1', 'fl_spese_bollo' => '0'],
            18 => ['codice' => 'E27', 'azienda_id' => null, 'descrizione' => 'E27/2011', 'descrizione_estesa' => 'Art. 27 COMMA 1,2,3 del D.L. 06/07/2011', 'natura' => null, 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            19 => ['codice' => 'E36', 'azienda_id' => null, 'descrizione' => 'Art 36 DL 41', 'descrizione_estesa' => 'Op esenti ATR. 36 DL 41/95', 'natura' => 'N5', 'fl_esenzione' => '1', 'fl_spese_bollo' => '0'],
            20 => ['codice' => 'E01', 'azienda_id' => null, 'descrizione' => 'Op. L. 190/14', 'descrizione_estesa' => 'Op. franchigia ART 1 COMMI 54/89 L.190/2014', 'natura' => 'N2.2', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            21 => ['codice' => 'Y07,4', 'azienda_id' => null, 'descrizione' => 'Non sog. 7 quat', 'descrizione_estesa' => 'Inversione contabile Ue 7-quarter) 633/72', 'natura' => 'N6', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            22 => ['codice' => 'Q01', 'azienda_id' => null, 'descrizione' => 'Esen. art. 124', 'descrizione_estesa' => 'Operazione esente 124) c.2 D.L. 34/2020', 'natura' => 'N3', 'fl_esenzione' => '1', 'fl_spese_bollo' => '1'],
            23 => ['codice' => '22', 'azienda_id' => null, 'descrizione' => 'IVA 22%', 'descrizione_estesa' => null, 'natura' => null, 'fl_esenzione' => '0', 'fl_spese_bollo' => '0', 'iva' => 22],
        ];

        foreach ($arr as $a) {
            DB::table('iva')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'codice' => $a['codice'],
                'azienda_id' => $a['azienda_id'],
                'iva' => $a['iva'] ?? 0,
                'descrizione' => $a['descrizione'],
                'descrizione_estesa' => $a['descrizione_estesa'],
                'natura' => $a['natura'],
                'fl_esenzione' => $a['fl_esenzione'],
                'fl_spese_bollo' => $a['fl_spese_bollo'],
            ]);
        }

    }
}
