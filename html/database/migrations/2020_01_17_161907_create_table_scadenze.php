<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableScadenze extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scadenze', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('azienda_id');
            $table->bigInteger('item_id');
            $table->string('item_controller', 25);
            $table->bigInteger('infosituata_moduli_details_id');
            $table->bigInteger('infosituata_moduli_details_scadenze_id');
            $table->date('start_at');
            $table->date('end_at');
            $table->integer('avvisa_entro_gg');
            $table->date('advice_at')->nullable();
            $table->enum('advice_item', ['0', '1'])->default('0');

            $table->timestamps();
        });

        Schema::create('gruppo_scadenza', function (Blueprint $table) {
            $table->bigInteger('gruppo_id');
            $table->bigInteger('scadenza_id');
            $table->timestamps();

            $table->unique(['gruppo_id', 'scadenza_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scadenze');
        Schema::dropIfExists('gruppo_scadenza');
    }
}
