<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessaggioTopicNotify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messaggio_topic_notify', function (Blueprint $table) {
            $table->bigInteger('messaggio_id');
            $table->bigInteger('utente_id');
            $table->timestamps();

            $table->index(['messaggio_id', 'utente_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messaggio_topic_notify');
    }
}
