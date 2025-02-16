<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGgScadenzee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infosituata_moduli_details_scadenze', function (Blueprint $table) {
            $table->integer('giorni')->default(0)->after('mesi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('infosituata_moduli_details_scadenze', function (Blueprint $table) {
            $table->dropColumn('giorni');
        });
    }
}
