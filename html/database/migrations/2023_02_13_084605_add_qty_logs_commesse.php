<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQtyLogsCommesse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse_log', function (Blueprint $table) {
            $table->integer('item_qty')->default(1)->after('item_label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commesse_log', function (Blueprint $table) {
            $table->dropColumn('item_qty');
        });
    }
}
