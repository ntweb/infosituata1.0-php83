<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackageToAzienda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aziende', function (Blueprint $table) {
            $table->bigInteger('package_id')->default(0)->after('sdi');
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
            $table->dropColumn('package_id');
        });
    }
}
