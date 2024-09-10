<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneNumberId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messaggio_whatsapp', function (Blueprint $table) {
            $table->string('phone_number_id', 25)->index()->nullable()->after('message');
        });

        Schema::table('aziende', function (Blueprint $table) {
            $table->string('module_whatsapp_phone_number_id', 25)->nullable()->after('module_whatsapp_token');
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
            $table->string('phone_number_id', 25)->index()->nullable()->after('message');
        });

        Schema::table('aziende', function (Blueprint $table) {
            $table->dropColumn('module_whatsapp_phone_number_id', 25);
        });
    }
}
