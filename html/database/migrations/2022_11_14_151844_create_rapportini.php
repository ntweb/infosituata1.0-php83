<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRapportini extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commesse_rapportini', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->unsignedBigInteger('commesse_id');
            $table->unsignedBigInteger('commesse_root_id');
            $table->string('titolo');
            $table->text('descrizione');
            $table->date('start');
            $table->string('livello')->default('info');
            $table->json('send_to_ids')->nullable();
            $table->unsignedBigInteger('users_id');
            $table->string('username', 50)->nullable();

            $table->timestamps();

            $table->foreign('commesse_id')->references('id')->on('commesse')->onDelete('cascade');
            $table->foreign('commesse_root_id')->references('id')->on('commesse')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commesse_rapportini');
    }
}
