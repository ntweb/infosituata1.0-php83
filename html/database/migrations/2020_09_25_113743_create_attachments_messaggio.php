<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsMessaggio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments_messaggio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('azienda_id');
            $table->bigInteger('messaggio_id');
            $table->string('label');
            $table->string('filename');
            $table->integer('size')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments_messaggio');
    }
}
