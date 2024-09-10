<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRapportiniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapportini', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->unsignedBigInteger('items_id')->nullable();
            $table->string('controller');
            $table->string('titolo');
            $table->text('descrizione');
            $table->date('start');
            $table->string('livello')->default('info');
            $table->json('send_to_ids')->nullable();
            $table->unsignedBigInteger('users_id');
            $table->string('username', 50)->nullable();

            $table->timestamps();

            $table->foreign('items_id')->references('id')->on('items')->onDelete('cascade');
        });

        Schema::create('rapportini_autorizzazioni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('azienda_id')->default(0);
            $table->string('controller');
            $table->json('gruppi_ids')->nullable();
            $table->timestamps();
        });

        Schema::table('aziende', function (Blueprint $table) {
            $table->enum('module_rapportini', ['0', '1'])->default('0')->after('module_commesse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rapportini');

        Schema::dropIfExists('rapportini_autorizzazioni');

        Schema::table('aziende', function (Blueprint $table) {
            $table->dropColumn('module_rapportini');
        });
    }
}
