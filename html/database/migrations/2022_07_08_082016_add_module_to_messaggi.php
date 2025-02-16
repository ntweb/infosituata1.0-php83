<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleToMessaggi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messaggi', function (Blueprint $table) {
            $table->enum('module', ['messaggio', 'sms', 'chat', 'manutenzione-mezzo'])->default('messaggio')->after('id');
            $table->unsignedBigInteger('manutenzioni_id')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messaggi', function (Blueprint $table) {
            $table->dropColumn('module');
            $table->dropColumn('manutenzioni_id');
        });
    }
}
