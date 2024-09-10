<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfosituataTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infosituata_moduli', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->string('module');
            $table->timestamps();
        });

        Schema::create('infosituata_moduli_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('infosituata_moduli_id');
            $table->string('label');
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
        Schema::dropIfExists('infosituata_moduli');
        Schema::dropIfExists('infosituata_moduli_details');
    }
}
