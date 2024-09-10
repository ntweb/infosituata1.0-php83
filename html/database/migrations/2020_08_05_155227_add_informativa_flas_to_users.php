<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInformativaFlasToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('privacy_fl_5')->nullable()->after('superadmin');
            $table->dateTime('privacy_fl_4')->nullable()->after('superadmin');
            $table->dateTime('privacy_fl_3')->nullable()->after('superadmin');
            $table->dateTime('privacy_fl_2')->nullable()->after('superadmin');
            $table->dateTime('privacy_fl_1')->nullable()->after('superadmin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('privacy_fl_5');
            $table->dropColumn('privacy_fl_4');
            $table->dropColumn('privacy_fl_3');
            $table->dropColumn('privacy_fl_2');
            $table->dropColumn('privacy_fl_1');
        });
    }
}
