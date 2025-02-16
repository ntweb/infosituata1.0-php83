<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRifTimbratureToLogCommesse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse_log', function (Blueprint $table) {
            $table->unsignedBigInteger('out_timbrature_id')->nullable()->after('note');
            $table->unsignedBigInteger('in_timbrature_id')->nullable()->after('note');

            $table->foreign('in_timbrature_id')->references('id')->on('timbrature')->onDelete('cascade');
            $table->foreign('out_timbrature_id')->references('id')->on('timbrature')->onDelete('cascade');
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
            $table->dropColumn('in_timbrature_id');
            $table->dropColumn('out_timbrature_id');
        });
    }
}
