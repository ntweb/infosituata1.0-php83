<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBroadcastToGruppi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gruppi', function (Blueprint $table) {
            $table->enum('broadcast', ['0', '1'])->after('label');
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
            $table->dropColumn('broadcast');
        });
    }
}
