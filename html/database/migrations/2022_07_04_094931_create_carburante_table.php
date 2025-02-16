<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarburanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('manutenzioni', function (Blueprint $table) {
            $table->decimal('litri', 30, 2)->default(0)->after('costo');
            $table->integer('km')->default(0)->after('costo');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manutenzioni', function (Blueprint $table) {
            $table->dropColumn('litri');
            $table->dropColumn('km');
        });

    }
}
