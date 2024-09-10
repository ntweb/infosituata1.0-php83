<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuliDetailsScadenze extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infosituata_moduli_details_scadenze', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('infosituata_moduli_details_id');
            $table->bigInteger('azienda_id')->default(0);
            $table->string('label');
            $table->text('description')->nullable();
            $table->integer('mesi')->default(0);
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
        Schema::dropIfExists('infosituata_moduli_details_scadenze');
    }
}
