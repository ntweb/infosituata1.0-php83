<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivacyToAziende extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aziende', function (Blueprint $table) {
            $table->string('email_contatto_privacy')->after('sdi')->nullable();
            $table->string('legale_rappresentante_email')->after('sdi')->nullable();
            $table->string('legale_rappresentante_tel')->after('sdi')->nullable();
            $table->string('legale_rappresentante')->after('sdi')->nullable();
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
            //
        });
    }
}
