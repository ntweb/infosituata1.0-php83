<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommesseAutorizzazioniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commesse_autorizzazioni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('commesse_root_id');
            $table->string('autorizzazione', 50);
            $table->json('users_ids')->nullable();
            $table->timestamps();

            $table->unique(['commesse_root_id', 'autorizzazione']);
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
        Schema::dropIfExists('commesse_autorizzazioni');
    }
}
