<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Hash;

class SuperAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            DB::table('users')->insert([
                'name' => 'Domenico Mecca',
                'email' => 'dm@artisanlab.it',
                'email_verified_at' => \Carbon\Carbon::now(),
                'password' => Hash::make('sa!qaz2wsx'),
                'superadmin' => '1',
                'active' => '1'
            ]);

            DB::table('users')->insert([
                'name' => 'Alessandro Lincesso',
                'email' => 'lincesso@libero.it',
                'email_verified_at' => \Carbon\Carbon::now(),
                'password' => Hash::make('123456'),
                'superadmin' => '1',
                'active' => '1'
            ]);

    }
}
