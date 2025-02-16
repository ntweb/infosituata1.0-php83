<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertRootRef extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->unsignedBigInteger('root_id')->nullable()->after('updated_at');
            $table->enum('fl_is_data_prevista_changeble', ['0', '1'])->default('0')->after('fl_is_status_changeble');
            $table->enum('fl_is_costo_changeble', ['0', '1'])->default('0')->after('fl_is_data_prevista_changeble');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->dropColumn('root_id');
            $table->dropColumn('fl_is_data_prevista_changeble');
            $table->dropColumn('fl_is_costo_changeble');
        });
    }
}
