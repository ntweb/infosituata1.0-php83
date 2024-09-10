<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManutenzioneMezziToGruppi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gruppi', function (Blueprint $table) {
            $table->enum('manutenzione_mezzi', ['0' , '1'])->default('0')->after('broadcast');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gruppi', function (Blueprint $table) {
            $table->dropColumn('manutenzione_mezzi');
        });
    }
}
