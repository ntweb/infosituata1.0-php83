<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMessaggiSede extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('messaggi', function (Blueprint $table) {
            $table->string('sedi_ids')->nullable()->after('priority');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('messaggi', function (Blueprint $table) {
            $table->dropColumn('sedi_ids');
        });

    }
}
