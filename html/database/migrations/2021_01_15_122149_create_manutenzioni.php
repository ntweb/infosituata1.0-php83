<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManutenzioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('ricambi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->string('label', 250);
            $table->timestamps();
        });

        Schema::create('manutenzioni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->unsignedBigInteger('items_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->date('data');
            $table->enum('tipo_1', ['ordinario', 'straordinario']);
            $table->enum('tipo_2', ['interno', 'esterno']);
            $table->integer('tempo')->default(0);
            $table->text('descrizione')->nullable();
            $table->timestamps();
        });

        Schema::create('manutenzioni_dettagli', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('manutenzioni_id');
            $table->unsignedBigInteger('ricambi_id')->nullable();
            $table->integer('magazzino')->default(0);
            $table->integer('acquistati')->default(0);
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
        Schema::dropIfExists('manutenzioni_dettagli');
        Schema::dropIfExists('manutenzioni');
        Schema::dropIfExists('ricambi');
    }
}
