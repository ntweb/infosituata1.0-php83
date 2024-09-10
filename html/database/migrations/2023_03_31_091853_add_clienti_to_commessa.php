<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientiToCommessa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->uuid('clienti_id')->nullable()->after('cliente');
            $table->foreign('clienti_id')->references('id')->on('clienti')->onDelete('set null');
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
            $table->dropColumn('clienti_id');
        });
    }
}
