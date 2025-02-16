<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFattureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fatture_documenti', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('descrizione');
            $table->string('sigla');
            $table->string('td')->unique();
            $table->timestamps();
        });

        Schema::create('fatture_incoterms', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('descrizione');
            $table->timestamps();
        });

        Schema::create('fatture', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('azienda_id')->index();
            $table->uuid('clienti_id')->index();
            $table->uuid('fatture_documenti_id')->index();
            $table->date('data');

            $table->integer('numero');
            $table->integer('anno');
            $table->string('partitario')->nullable();


            $table->string('trasporto_indirizzo')->nullable();
            $table->string('trasporto_cap')->nullable();
            $table->string('trasporto_citta')->nullable();
            $table->string('trasporto_provincia')->nullable();
            $table->string('trasporto_paese')->nullable();
            $table->string('trasporto_vettore')->nullable();
            $table->string('trasporto_incoterms_id')->nullable();
            $table->integer('trasporto_colli')->default(0);
            $table->decimal('trasporto_peso', 30,4)->default(0);
            $table->dateTime('trasporto_data')->nullable();

            $table->string('tipo_pagamento')->nullable();
            $table->decimal('iva', 30,4)->default(0);
            $table->decimal('totale_documento', 30,4)->default(0);
            $table->decimal('pagato', 30,4)->default(0);
            $table->decimal('abbuono', 30,4)->default(0);

            $table->timestamps();

            $table->unique(['clienti_id', 'numero', 'anno', 'partitario']);
            $table->foreign('trasporto_incoterms_id')->references('id')->on('fatture_incoterms')->onDelete('cascade');
        });

        Schema::create('fatture_dettagli', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('fatture_id');

            $table->timestamps();

            $table->foreign('fatture_id')->references('id')->on('fatture')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fatture_dettagli');
        Schema::dropIfExists('fatture');
        Schema::dropIfExists('fatture_incoterms');
        Schema::dropIfExists('fatture_documenti');
    }
}
