<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommesseToScadenze extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->unsignedBigInteger('commesse_id')->default(0)->after('item_controller');
            $table->string('label')->nullable()->after('infosituata_moduli_details_scadenze_id');
            $table->text('description')->nullable()->after('label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->dropColumn('commesse_id');
            $table->dropColumn('label');
            $table->dropColumn('description');
        });
    }
}
