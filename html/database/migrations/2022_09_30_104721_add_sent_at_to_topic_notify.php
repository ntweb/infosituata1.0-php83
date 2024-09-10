<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSentAtToTopicNotify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messaggio_topic_notify', function (Blueprint $table) {
            $table->dateTime('sent_at')->nullable()->after('utente_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messaggio_topic_notify', function (Blueprint $table) {
            $table->dropColumn('sent_at');
        });
    }
}
