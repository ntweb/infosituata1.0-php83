<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProdFieldToChecklistTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checklists_templates', function (Blueprint $table) {
            $table->enum('fl_prod', ['0', '1'])->default('0')->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checklists_templates', function (Blueprint $table) {
            $table->dropColumn('fl_prod');
        });
    }
}
