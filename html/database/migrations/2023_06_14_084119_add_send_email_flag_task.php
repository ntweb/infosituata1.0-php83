<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendEmailFlagTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('fl_notify_task_completed', ['0', '1'])->default('0')->after('users_ids');
            $table->json('notify_gruppi_ids')->nullable()->after('users_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('fl_notify_task_completed');
            $table->dropColumn('notify_gruppi_ids')->nullable()->after('users_ids');
        });
    }
}
