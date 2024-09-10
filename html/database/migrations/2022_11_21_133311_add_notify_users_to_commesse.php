<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotifyUsersToCommesse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->json('notification_users_ids')->nullable()->after('auth');
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
            $table->dropColumn('notification_users_ids');
        });
    }
}
