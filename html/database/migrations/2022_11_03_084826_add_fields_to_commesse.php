<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCommesse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->enum('fl_is_status_changeble', ['0', '1'])->default('0')->after('stato');
            $table->enum('fl_can_have_sottofase', ['0', '1'])->default('0')->after('fl_is_status_changeble');
            $table->enum('fl_can_have_item', ['0', '1'])->default('0')->after('fl_can_have_sottofase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->dropColumn('fl_is_status_changeble');
            $table->dropColumn('fl_can_have_sottofase');
            $table->dropColumn('fl_can_have_item');
        });
    }
}
