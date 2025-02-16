<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Hash;

class ModuliTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Utente
        DB::table('infosituata_moduli')->insert([
            'label' => 'Utente',
            'module' => 'utente'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 1,
            'label' => 'Formazione'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 1,
            'label' => 'Contrattualistica'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 1,
            'label' => 'Idoneità sanitaria'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 1,
            'label' => 'Altro'
        ]);

        // Attrezzature e mezzi
        DB::table('infosituata_moduli')->insert([
            'label' => 'Attrezzature e mezzi',
            'module' => 'attrezzature'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 2,
            'label' => 'Verifiche periodiche'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 2,
            'label' => 'Assicurazione'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 2,
            'label' => 'Tasse e bolli'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 2,
            'label' => 'Revisione'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 2,
            'label' => 'Tagliando'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 2,
            'label' => 'Collaudi'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 2,
            'label' => 'COntrolli'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 2,
            'label' => 'Rinnovi'
        ]);

        DB::table('infosituata_moduli_details')->insert([
            'infosituata_moduli_id' => 2,
            'label' => 'Altro'
        ]);

    }
}
