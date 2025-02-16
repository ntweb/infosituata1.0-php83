<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemFieldsToLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse_log', function (Blueprint $table) {
            $table->bigInteger('item_id')->nullable()->after('commesse_id');
            $table->string('item_label')->nullable()->after('item_id');
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
            $table->dropColumn('item_id');
            $table->dropColumn('item_label');
        });
    }
}
