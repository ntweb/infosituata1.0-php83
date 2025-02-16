<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommessaToTimbratura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timbrature', function (Blueprint $table) {
            $table->unsignedBigInteger('commesse_id')->nullable()->after('users_id');
            $table->string('commesse_label')->nullable('commesse_id');

            $table->foreign('commesse_id')->references('id')->on('commesse')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timbrature', function (Blueprint $table) {
            $table->dropColumn('commesse_id');
            $table->dropColumn('commesse_label');
        });
    }
}
