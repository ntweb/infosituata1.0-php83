<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSmsUserProvider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aziende', function (Blueprint $table) {
            $table->string('module_sms_provider_password', 100)->nullable()->after('module_sms_provider');
            $table->string('module_sms_provider_username', 100)->nullable()->after('module_sms_provider');
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
            $table->dropColumn('module_sms_provider_password');
            $table->dropColumn('module_sms_provider_username');
        });
    }
}
