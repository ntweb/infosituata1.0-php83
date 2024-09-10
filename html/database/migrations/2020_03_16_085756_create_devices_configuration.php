<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices_configuration', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('azienda_id');
            $table->bigInteger('device_id')->default(0);
            $table->smallInteger('hrm_bpm_min')->default(40);
            $table->smallInteger('hrm_bpm_max')->default(210);
            $table->enum('geo_refresh', ['0', '1'])->default('0');
            $table->string('telephones_alert')->nullable();
            $table->string('emails_alert')->nullable();
            $table->dateTime('request_configuration_update')->nullable();
            $table->enum('active', ['0', '1'])->default('0')->comment('Valid only for device');
            $table->timestamps();

            $table->unique(['azienda_id', 'device_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices_configuration');
    }
}
