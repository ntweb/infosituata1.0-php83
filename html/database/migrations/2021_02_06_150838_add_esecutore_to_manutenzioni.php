<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEsecutoreToManutenzioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manutenzioni', function (Blueprint $table) {
            $table->string('esecutore', 250)->nullable()->after('tipo_2');
            $table->decimal('costo', 10,2)->default(0)->after('descrizione');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manutenzioni', function (Blueprint $table) {
            $table->dropColumn('esecutore');
            $table->dropColumn('costo');
        });
    }
}
