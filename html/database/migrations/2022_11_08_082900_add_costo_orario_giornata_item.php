<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostoOrarioGiornataItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->decimal('costo_item_giornaliero_previsto', 10,2)->default(0)->after('data_fine_effettiva');
            $table->decimal('costo_item_orario_previsto', 10,2)->default(0)->after('costo_item_giornaliero_previsto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->dropColumn('costo_item_giornaliero_previsto');
            $table->dropColumn('costo_item_orario_previsto');
        });
    }
}
