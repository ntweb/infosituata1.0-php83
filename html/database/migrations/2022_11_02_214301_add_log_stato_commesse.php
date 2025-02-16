<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogStatoCommesse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commesse_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('commesse_id')->index();
            $table->string('stato')->nullable();
            $table->dateTime('inizio')->nullable();
            $table->dateTime('fine')->nullable();
            $table->text('note')->nullable();
            $table->string('username')->nullable();
            $table->timestamps();

            $table->foreign('commesse_id')->references('id')->on('commesse')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commesse_log');
    }
}
