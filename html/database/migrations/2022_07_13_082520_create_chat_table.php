<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messaggio_topic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('messaggio_id');
            $table->unsignedBigInteger('users_id');
            $table->text('messaggio')->nullable();
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
        Schema::dropIfExists('messaggio_topic');
    }
}
