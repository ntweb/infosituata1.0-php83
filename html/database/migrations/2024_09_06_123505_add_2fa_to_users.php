<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add2faToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('_2fa')->default(false)->after('permission_checked_at');
            $table->string('_2fa_code')->nullable()->after('_2fa');
            $table->dateTime('_2fa_code_expiration')->nullable()->after('_2fa_code');
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
            $table->dropColumn('_2fa');
            $table->dropColumn('_2fa_code');
            $table->dropColumn('_2fa_code_expiration');
        });
    }
}
