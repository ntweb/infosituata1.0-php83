<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimbraturePermessiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timbrature_permessi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->unsignedBigInteger('users_id');
            $table->dateTime('requested_at')->nullable();
            $table->text('type')->nullable();
            $table->text('note')->nullable();
            $table->text('note_office')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();

            $table->string('status')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::table('items_eventi', function (Blueprint $table) {
            $table->unsignedBigInteger('timbrature_permessi_id')->nullable()->after('items_id');
            $table->foreign('timbrature_permessi_id')->references('id')->on('timbrature_permessi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items_eventi', function (Blueprint $table) {
            $table->dropForeign('items_eventi_timbrature_permessi_id_foreign');
            $table->dropColumn('timbrature_permessi_id');
        });

        Schema::dropIfExists('timbrature_permessi');
    }
}
