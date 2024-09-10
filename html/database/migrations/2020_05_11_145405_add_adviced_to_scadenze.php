<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdvicedToScadenze extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->enum('adviced', ['0', '1'])->after('advice_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->dropColumn('adviced');
        });
    }
}
