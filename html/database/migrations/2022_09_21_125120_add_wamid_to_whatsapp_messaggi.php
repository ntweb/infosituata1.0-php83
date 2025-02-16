<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWamidToWhatsappMessaggi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messaggio_whatsapp', function (Blueprint $table) {
            $table->string('wamid')->nullable()->index()->after('sent_at');
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
            $table->dropColumn('wamid');
        });
    }
}
