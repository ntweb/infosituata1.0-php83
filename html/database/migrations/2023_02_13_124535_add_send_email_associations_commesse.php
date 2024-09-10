<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendEmailAssociationsCommesse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->enum('fl_send_email_association', ['0', '1'])->default('1')->after('fl_can_have_item');
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
            $table->dropColumn('fl_send_email_association');
        });
    }
}
