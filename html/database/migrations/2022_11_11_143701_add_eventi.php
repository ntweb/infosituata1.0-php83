<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_eventi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->unsignedBigInteger('items_id')->index();
            $table->string('titolo');
            $table->text('descrizione');
            $table->date('start');
            $table->date('end');
            $table->string('livello')->default('info');
            $table->string('username', 50)->nullable();
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
        Schema::dropIfExists('items_eventi');
    }
}
