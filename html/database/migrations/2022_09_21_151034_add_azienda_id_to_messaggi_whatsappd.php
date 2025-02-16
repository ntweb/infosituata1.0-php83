<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAziendaIdToMessaggiWhatsappd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messaggio_whatsapp', function (Blueprint $table) {
            $table->unsignedBigInteger('azienda_id')->after('id');
        });

        DB::statement('ALTER TABLE messaggio_whatsapp CHANGE COLUMN messaggio_id messaggio_id BIGINT(20) NULL AFTER id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messaggio_whatsapp', function (Blueprint $table) {
            $table->dropColumn('azienda_id');
        });
    }
}
