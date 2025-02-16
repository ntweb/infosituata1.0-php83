<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToManutenzioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manutenzioni', function (Blueprint $table) {
            $table->enum('type', ['manutenzione', 'controllo', 'carburante'])->after('items_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manutenzioni', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
