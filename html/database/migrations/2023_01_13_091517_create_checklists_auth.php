<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklistsAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists_autorizzazioni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('azienda_id')->default(0);
            $table->string('reference_controller');
            $table->json('gruppi_ids')->nullable();
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
        Schema::dropIfExists('checklists_autorizzazioni');
    }
}
