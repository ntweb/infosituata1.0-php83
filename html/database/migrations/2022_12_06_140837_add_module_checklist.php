<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleChecklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aziende', function (Blueprint $table) {
            $table->enum('module_checklist', ['0', '1'])->default('0')->after('module_commesse');
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
            $table->dropColumn('module_checklist');
        });
    }
}
