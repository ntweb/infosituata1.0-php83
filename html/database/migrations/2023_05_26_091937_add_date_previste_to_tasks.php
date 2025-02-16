<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatePrevisteToTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dateTime('data_fine_prevista')->nullable()->after('tasks_template_id');
            $table->dateTime('data_inizio_prevista')->nullable()->after('tasks_template_id');
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
            $table->dropColumn('data_fine_prevista');
            $table->dropColumn('data_inizio_prevista');
            $table->dropColumn('users_ids');
        });
    }
}
