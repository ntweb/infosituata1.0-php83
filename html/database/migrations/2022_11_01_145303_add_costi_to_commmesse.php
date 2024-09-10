<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostiToCommmesse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->decimal('costo_effettivo', 10,2)->default(0)->after('data_fine_effettiva');
            $table->decimal('costo_previsto', 10,2)->default(0)->after('data_fine_effettiva');
            $table->decimal('prezzo_cliente', 10,2)->default(0)->after('costo_effettivo');
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
            $table->dropColumn('costo_effettivo');
            $table->dropColumn('costo_previsto');
            $table->dropColumn('prezzo_cliente');
        });
    }
}
