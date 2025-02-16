<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlCloudToUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->string('url_cloud')->nullable()->after('filename');
        });

        Schema::table('attachments_commessa', function (Blueprint $table) {
            $table->string('url_cloud')->nullable()->after('filename');
        });

        Schema::table('attachments_manutenzione', function (Blueprint $table) {
            $table->string('url_cloud')->nullable()->after('filename');
        });

        Schema::table('attachments_messaggio', function (Blueprint $table) {
            $table->string('url_cloud')->nullable()->after('filename');
        });

        Schema::table('attachments_modot23', function (Blueprint $table) {
            $table->string('url_cloud')->nullable()->after('filename');
        });

        Schema::table('attachments_scadenza', function (Blueprint $table) {
            $table->string('url_cloud')->nullable()->after('filename');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropColumn('url_cloud');
        });

        Schema::table('attachments_commessa', function (Blueprint $table) {
            $table->dropColumn('url_cloud');
        });

        Schema::table('attachments_manutenzione', function (Blueprint $table) {
            $table->dropColumn('url_cloud');
        });

        Schema::table('attachments_messaggio', function (Blueprint $table) {
            $table->dropColumn('url_cloud');
        });

        Schema::table('attachments_modot23', function (Blueprint $table) {
            $table->dropColumn('url_cloud');
        });

        Schema::table('attachments_scadenza', function (Blueprint $table) {
            $table->dropColumn('url_cloud');
        });
    }
}
