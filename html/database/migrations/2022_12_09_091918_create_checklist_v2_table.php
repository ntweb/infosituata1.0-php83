<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklistV2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('checklists');

        Schema::create('checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->unsignedBigInteger('checklists_templates_id');
            $table->unsignedBigInteger('reference_id');
            $table->string('reference_controller');
            $table->unsignedBigInteger('users_id');
            $table->string('username');
            $table->timestamps();
        });

        Schema::create('checklists_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('checklists_id');
            $table->uuid('key');
            $table->string('value')->nullable();
            $table->text('value_big')->nullable();
            $table->timestamps();

            $table->unique(['checklists_id', 'key']);
            $table->foreign('checklists_id')->references('id')->on('checklists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklists_data');
        Schema::dropIfExists('checklists');
    }
}
