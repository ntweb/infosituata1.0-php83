<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWhatsappFieldsToAzienda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aziende', function (Blueprint $table) {
            $table->text('module_whatsapp_token')->nullable()->after('module_whatsapp');
            $table->text('module_whatsapp_endpoint')->nullable()->after('module_whatsapp');
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
            $table->dropColumn('module_whatsapp_token');
            $table->dropColumn('module_whatsapp_endpoint');
        });
    }
}
