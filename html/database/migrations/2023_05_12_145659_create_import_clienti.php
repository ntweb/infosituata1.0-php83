<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportClienti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clienti_import_file', function (Blueprint $table) {
            $table->unsignedBigInteger('azienda_id')->index();
            $table->string('filename')->index();
            $table->text('error')->nullable();
            $table->timestamps();
        });

        Schema::create('clienti_import', function (Blueprint $table) {
            $table->unsignedBigInteger('azienda_id')->index();
            $table->uuid('clienti_id_update');
            $table->string('rs')->nullable();
            $table->string('nome')->nullable();
            $table->string('cognome')->nullable();
            $table->string('piva')->nullable();
            $table->string('cf')->nullable();
            $table->string('indirizzo')->nullable();
            $table->string('cap')->nullable();
            $table->string('citta')->nullable();
            $table->string('provincia')->nullable();
            $table->string('telefono')->nullable();
            $table->string('sdi')->nullable();
            $table->string('pec')->nullable();
            $table->string('tipo_operazione')->nullable();
            $table->string('log')->nullable();
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
        Schema::dropIfExists('clienti_import_file');
        Schema::dropIfExists('clienti_import');
    }
}
