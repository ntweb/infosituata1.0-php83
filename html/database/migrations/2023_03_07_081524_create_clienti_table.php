<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clienti', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('azienda_id')->index();
            $table->string('rs')->nullable();
            $table->string('cognome')->nullable();
            $table->string('nome')->nullable();
            $table->date('data_nascita')->nullable();
            $table->string('piva')->nullable();
            $table->string('cf')->nullable();
            $table->string('res_fiscale')->nullable()->comment('Italia, CEE, Estero, RSM, Vaticano');
            $table->string('stato')->nullable();
            $table->string('indirizzo')->nullable();
            $table->string('cap')->nullable();
            $table->string('citta')->nullable();
            $table->string('provincia')->nullable();
            $table->string('telefono')->nullable();
            $table->string('pec')->nullable();
            $table->string('tipo_fattura')->default('b2b')->comment('Fattura B2B, Fattura PA');
            $table->string('sdi')->nullable();
            $table->enum('fl_addebito_bollo', ['0', '1'])->default('0');
            $table->enum('fl_split_payment', ['0', '1'])->default('0');
            $table->date('fl_split_payment_da_data')->nullable();
            $table->enum('fl_ente_pubblico', ['0', '1'])->default('0');
            $table->enum('fl_soggetto_privato', ['0', '1'])->default('0');
            $table->enum('fl_persona_fisica', ['0', '1'])->default('0');

            $table->timestamps();
        });

        Schema::create('iva', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('codice');
            $table->unsignedBigInteger('azienda_id')->nullable();
            $table->decimal('iva', 10,2)->default(0);
            $table->string('descrizione')->nullable();
            $table->string('descrizione_estesa')->nullable();
            $table->string('natura')->nullable();
            $table->enum('fl_esenzione', ['0', '1'])->default('0');
            $table->enum('fl_spese_bollo', ['0', '1'])->default('0');
            $table->timestamps();

            $table->unique(['codice', 'azienda_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clienti');
        Schema::dropIfExists('iva');
    }
}
