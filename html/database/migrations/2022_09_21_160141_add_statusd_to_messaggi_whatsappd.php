<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusdToMessaggiWhatsappd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messaggio_whatsapp', function (Blueprint $table) {
            $table->string('status', 25)->nullable()->after('wamid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messaggio_whatsapp', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
