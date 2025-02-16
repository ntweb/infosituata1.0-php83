<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('azienda_id');
            $table->string('controller', 25);
            $table->string('extras1')->nullable();
            $table->string('extras2')->nullable();
            $table->string('extras3')->nullable();
            $table->string('extras4')->nullable();
            $table->string('extras5')->nullable();
            $table->string('extras6')->nullable();
            $table->string('extras7')->nullable();
            $table->string('extras8')->nullable();
            $table->string('extras9')->nullable();
            $table->string('extras10')->nullable();
            $table->timestamps();
        });

        Schema::dropIfExists('utenti');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
