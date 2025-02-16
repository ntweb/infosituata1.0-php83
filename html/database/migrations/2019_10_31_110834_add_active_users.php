<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('active', ['0', '1'])->default('0')->after('remember_token');
            $table->enum('superadmin', ['0', '1'])->default('0')->after('remember_token');
            $table->bigInteger('azienda_id')->default(0)->after('remember_token');
            $table->bigInteger('utente_id')->default(0)->after('azienda_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->dropColumn('superadmin');
            $table->dropColumn('azienda_id');
            $table->dropColumn('utente_id');
        });
    }
}
