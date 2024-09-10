<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsS3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachmentss3', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('azienda_id')->index();
            $table->unsignedBigInteger('reference_id')->index();
            $table->string('reference_table')->index();
            $table->string('label');
            $table->string('filename');
            $table->string('url_cloud')->nullable();
            $table->integer('size')->nullable();
            $table->enum('is_public', ['0', '1'])->default('1')->comment('Se 1 verrà visto solo da power user e utente stesso');
            $table->enum('is_embedded', ['0', '1'])->default('0')->comment('Usato se si vuole allegare solo per riferimento in HTML');
            $table->enum('to_delete', ['0', '1'])->default('0');
            $table->unsignedBigInteger('users_id')->comment('Utente che ha effettuato l\'upload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachmentss3');
    }
}
