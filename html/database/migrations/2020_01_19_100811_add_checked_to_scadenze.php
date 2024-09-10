<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckedToScadenze extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->bigInteger('parent_id')->default(0)->after('advice_item');
            $table->dateTime('checked_at')->nullable()->after('advice_item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumt('checked_at');
        });
    }
}
