<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRapportinoUpload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attachments_commessa', function (Blueprint $table) {
            $table->unsignedBigInteger('commesse_rapportini_id')->nullable()->after('commesse_id');

            $table->foreign('commesse_rapportini_id')->references('id')->on('commesse_rapportini')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attachments_commessa', function (Blueprint $table) {
            $table->dropColumn('commesse_rapportini_id');
        });
    }
}
