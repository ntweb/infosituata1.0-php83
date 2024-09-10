<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManutenzioneMezziToMessaggi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messaggi', function (Blueprint $table) {
            // $table->unsignedBigInteger('manutenzioni_id')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messaggi', function (Blueprint $table) {
            $table->dropColumn('manutenzioni_id');
        });
    }
}
