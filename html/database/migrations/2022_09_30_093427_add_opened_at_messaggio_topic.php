<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpenedAtMessaggioTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messaggio_topic', function (Blueprint $table) {
            $table->dateTime('opened_at')->nullable()->after('messaggio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messaggio_topic', function (Blueprint $table) {
            $table->dropColumn('opened_at');
        });
    }
}
