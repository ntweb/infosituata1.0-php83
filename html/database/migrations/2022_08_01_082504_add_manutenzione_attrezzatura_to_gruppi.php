<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManutenzioneAttrezzaturaToGruppi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gruppi', function (Blueprint $table) {
            $table->enum('manutenzione_attrezzatura', ['0' , '1'])->default('0')->after('broadcast');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gruppi', function (Blueprint $table) {
            $table->dropColumn('manutenzione_attrezzatura');
        });
    }
}
