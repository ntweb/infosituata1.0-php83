<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksAutorizzazioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_autorizzazioni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('tasks_root_id');
            $table->string('autorizzazione', 50);
            $table->json('users_ids')->nullable();
            $table->timestamps();

            $table->unique(['tasks_root_id', 'autorizzazione']);
            $table->foreign('tasks_root_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_autorizzazioni');
    }
}
