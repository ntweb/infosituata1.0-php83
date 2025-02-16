<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtrafieldsToCommesse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commesse_templates', function (Blueprint $table) {
            $table->text('extra_fields')->nullable()->after('color');
        });

        Schema::table('commesse', function (Blueprint $table) {
            $table->text('extra_fields')->nullable()->after('color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commesse_templates', function (Blueprint $table) {
            $table->dropColumn('extra_fields');
        });

        Schema::table('commesse', function (Blueprint $table) {
            $table->dropColumn('extra_fields');
        });
    }
}
