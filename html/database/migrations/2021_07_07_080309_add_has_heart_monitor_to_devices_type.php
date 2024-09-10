<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasHeartMonitorToDevicesType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices_type', function (Blueprint $table) {
            $table->enum('heart_monitor', ['0', '1'])->after('hw');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices_type', function (Blueprint $table) {
            $table->dropColumn('heart_monitor');
        });
    }
}
