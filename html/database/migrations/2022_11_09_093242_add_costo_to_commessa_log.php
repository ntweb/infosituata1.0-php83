<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostoToCommessaLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse_log', function (Blueprint $table) {
            $table->decimal('item_costo', 10,2)->default(0)->after('item_label');
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
            $table->dropColumn('item_costo');
        });
    }
}
