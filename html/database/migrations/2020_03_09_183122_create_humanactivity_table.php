<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHumanactivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('humanactivity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('device_id');
            $table->bigInteger('utente_id');
            $table->bigInteger('azienda_id');
            $table->enum('stress_level', ['nd','normal','warning','critical'])->default('nd');
            $table->enum('hrm', ['nd','normal','warning','critical'])->default('nd');
            $table->tinyInteger('hrm_bpm')->default(0);
            $table->enum('man_down', ['up', 'down'])->default('up');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('alert', ['auto', 'manual'])->default('auto');
            $table->dateTime('checked_at')->nullable();
            $table->bigInteger('checked_by')->default(0);
            $table->timestamps();
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('utente_id')->default(0);
            $table->bigInteger('azienda_id');
            $table->bigInteger('device_type_id');
            $table->string('label')->nullable();
            $table->string('identifier', 100);
            $table->enum('active', ['0', '1'])->default('1');
            $table->timestamps();

            $table->unique(['identifier']);
        });

        Schema::create('devices_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('brand')->nullable();
            $table->string('label')->nullable();
            $table->string('os')->nullable();
            $table->string('version')->nullable();
            $table->enum('hw', ['smartwatch', 'smartphone', 'laptop', 'pc', 'tablet'])->default('smartwatch');
            $table->timestamps();
        });

        Schema::table('aziende', function (Blueprint $table) {
            $table->integer('terminali')->default(0)->after('sdi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('humanactivity');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('devices_type');

        Schema::table('aziende', function (Blueprint $table) {
            $table->dropColumn('terminali');
        });

    }
}
