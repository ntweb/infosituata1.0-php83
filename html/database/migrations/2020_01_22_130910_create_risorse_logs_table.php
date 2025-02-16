<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRisorseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risorse_logs', function (Blueprint $table) {

            $table->bigInteger('azienda_id');
            $table->bigInteger('utente_id');
            $table->bigInteger('item_id');
            $table->timestamps();

            $table->unique(['utente_id', 'item_id', 'created_at']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('risorse_logs');
    }
}
