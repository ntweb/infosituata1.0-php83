<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateCommesseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commesse', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('azienda_id');
            $table->bigInteger('item_id')->nullable();
            $table->string('label');
            $table->string('item_label')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('execute_after_id')->nullable();
            $table->string('time', 10)->nullable();
            $table->string('color', 10)->nullable();
            $table->string('cliente')->nullable();
            $table->string('protocollo')->nullable();
            $table->string('tags')->nullable();
            $table->unsignedBigInteger('commesse_template_id')->nullable();
            $table->date('data_inizio_prevista')->nullable();
            $table->date('data_fine_prevista')->nullable();
            $table->date('data_inizio_effettiva')->nullable();
            $table->date('data_fine_effettiva')->nullable();
            $table->timestamps();

            // $table->nestedSet();
            NestedSet::columns($table);
            $table->foreign('commesse_template_id')->references('id')->on('commesse_templates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commesse');
    }
}
