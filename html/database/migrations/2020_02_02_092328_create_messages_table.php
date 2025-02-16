<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messaggi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('azienda_id');
            $table->bigInteger('user_id');
            $table->string('oggetto');
            $table->longText('messaggio')->nullable();
            $table->enum('priority', ['standard', 'important'])->default('standard');
            $table->string('gruppi_ids')->nullable();
            $table->string('utenti_ids')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('messaggio_utente', function (Blueprint $table) {
            $table->bigInteger('messaggio_id');
            $table->bigInteger('utente_id');
            $table->dateTime('opened_at')->nullable();
            $table->timestamps();

            $table->unique(['messaggio_id', 'utente_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messaggio_utente');
        Schema::dropIfExists('messaggi');
    }
}
