<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfosToScadenze extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->bigInteger('checked_by')->default(0)->after('advice_item');
            $table->bigInteger('updated_by')->default(0)->after('advice_item');
            $table->bigInteger('created_by')->default(0)->after('advice_item');
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
            $table->dropColumn('checked_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('created_by');
        });
    }
}
