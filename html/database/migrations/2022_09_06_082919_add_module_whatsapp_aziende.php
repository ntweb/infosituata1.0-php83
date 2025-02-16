<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleWhatsappAziende extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aziende', function (Blueprint $table) {
            $table->enum('module_whatsapp', ['0', '1'])->default('0')->after('module_sms_provider_refresh');
            $table->string('module_whatsapp_tel')->nullable()->after('module_whatsapp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aziende', function (Blueprint $table) {
            $table->dropColumn('module_whatsapp');
            $table->dropColumn('module_whatsapp_tel');
        });
    }
}
