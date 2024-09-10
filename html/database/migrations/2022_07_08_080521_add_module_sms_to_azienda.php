<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleSmsToAzienda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aziende', function (Blueprint $table) {
            $table->enum('module_sms_provider_refresh', ['0', '1'])->default('0')->after('module_timbrature');
            $table->integer('module_sms_provider_credits')->default(0)->after('module_timbrature');
            $table->string('module_sms_provider', 100)->nullable()->after('module_timbrature');
            $table->enum('module_sms', ['0', '1'])->default('0')->after('module_timbrature');
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
            $table->dropColumn('module_sms_provider_refresh');
            $table->dropColumn('module_sms_provider_credits');
            $table->dropColumn('module_sms_provider');
            $table->dropColumn('module_sms');
        });
    }
}
