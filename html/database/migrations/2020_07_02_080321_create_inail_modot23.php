<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInailModot23 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inail_modot23', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->unsignedBigInteger('user_id');
            $table->string('reparto')->nullable();
            $table->string('qualifica')->nullable();
            $table->string('tipo_lavoratore')->nullable();
            $table->integer('n')->default(0);
            $table->integer('anno')->default(0);
            $table->dateTime('data_e_ora')->nullable();
            $table->string('nome_e_cognome')->nullable();
            $table->string('tipologia')->nullable();
            $table->string('tipo_incidente');
            $table->text('descrizione_incidente')->nullable();
            $table->text('prop_elim_pericolo')->nullable();
            $table->text('preposto')->nullable();
            $table->text('categoria')->nullable();
            $table->text('analisi_cause_problema')->nullable();
            $table->text('azioni_da_intr')->nullable();
            $table->text('stato_azioni_da_intr')->nullable();
            $table->text('resp_attuazione')->nullable();
            $table->text('term_attuazione')->nullable();
            $table->enum('status', ['active', 'canceled']);
            $table->unsignedBigInteger('updated_users_id')->default(0);
            $table->timestamps();

            $table->unique(['azienda_id', 'n', 'anno']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inail_modot23');
    }
}
