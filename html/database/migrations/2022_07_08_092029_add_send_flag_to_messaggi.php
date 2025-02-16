<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendFlagToMessaggi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messaggi', function (Blueprint $table) {
            $table->text('exception')->nullable()->after('sent_at');
            $table->enum('to_send', ['0', '1'])->default('0')->after('sent_at');
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
            $table->dropColumn('exception');
            $table->dropColumn('to_send');
        });
    }
}
