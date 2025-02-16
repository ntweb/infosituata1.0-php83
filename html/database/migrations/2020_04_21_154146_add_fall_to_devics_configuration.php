<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFallToDevicsConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices_configuration', function (Blueprint $table) {
            $table->smallInteger('fall_threshold')->default(0)->after('geo_refresh')->comment('0 => standard, 1 => soft');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices_configuration', function (Blueprint $table) {
            $table->dropColumn('fall_threshold');
        });
    }
}
