<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQrComemsseLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse_log', function (Blueprint $table) {
            $table->enum('fl_qr', ['0', '1'])->default('0')->after('out_timbrature_id');
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
            $table->dropColumn('fl_qr');
        });
    }
}
