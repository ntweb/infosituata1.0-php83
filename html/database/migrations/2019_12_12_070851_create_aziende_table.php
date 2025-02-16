<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAziendeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aziende', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->string('citta');
            $table->string('provincia');
            $table->string('cap', 10);
            $table->string('indirizzo');
            $table->string('telefono')->nullable();
            $table->string('pec')->nullable();
            $table->string('piva')->nullable();
            $table->string('codfisc')->nullable();
            $table->string('sdi')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->date('deactivate_at')->nullable()->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aziende');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('deactivate_at');
        });
    }
}
