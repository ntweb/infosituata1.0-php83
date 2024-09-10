<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimbratureModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timbrature', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->unsignedBigInteger('users_id');
            $table->dateTime('marked_at');
            $table->dateTime('marked_at_backup')->nullable();
            $table->enum('type', ['in', 'out'])->default('in');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['users_id', 'marked_at']);
        });

        Schema::table('aziende', function (Blueprint $table) {
            $table->enum('module_timbrature', ['0', '1'])->default('0')->after('note');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('module_timbrature', ['0', '1'])->default('1')->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timbrature');

        Schema::table('aziende', function (Blueprint $table) {
            $table->dropColumn('module_timbrature');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('module_timbrature');
        });

    }
}
