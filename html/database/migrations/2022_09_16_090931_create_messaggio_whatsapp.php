<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessaggioWhatsapp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messaggio_whatsapp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('messaggio_id');
            $table->bigInteger('utente_id');
            $table->text('message')->nullable();
            $table->enum('from', ['business', 'guest'])->default('business');
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('opened_at')->nullable();
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
        Schema::dropIfExists('messaggio_whatsapp');
    }
}
