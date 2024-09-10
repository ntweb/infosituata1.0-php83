<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CraeteSquadre extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squadre', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('squadre_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('squadre_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('costo_item_orario_previsto', 10,2)->default(0);
            $table->timestamps();

            $table->unique(['squadre_id', 'item_id']);

            $table->foreign('squadre_id')->references('id')->on('squadre')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('squadre_items');
        Schema::dropIfExists('squadre');
    }
}
