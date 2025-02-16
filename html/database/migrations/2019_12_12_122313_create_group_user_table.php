<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gruppo_utente', function (Blueprint $table) {
            $table->bigInteger('gruppo_id');
            $table->bigInteger('utente_id');
            $table->timestamps();

            $table->unique(['gruppo_id', 'utente_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gruppo_utente');
    }
}
