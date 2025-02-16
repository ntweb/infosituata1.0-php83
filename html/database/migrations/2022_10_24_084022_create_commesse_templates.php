<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateCommesseTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commesse_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('azienda_id');
            $table->bigInteger('item_id')->nullable();
            $table->string('label');
            $table->string('item_label')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('execute_after_id')->nullable();
            $table->string('time', 10)->nullable();
            $table->string('color', 10)->nullable();
            $table->timestamps();

            // $table->nestedSet();
            NestedSet::columns($table);

            $table->foreign('execute_after_id')->references('id')->on('commesse_templates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commesse_templates');
    }
}
