<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cisterne', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->decimal('livello_attuale', 30, 2)->default(0);
            $table->decimal('livello_minimo', 30, 2)->default(0);
            $table->json('gruppi_ids');
            $table->timestamps();
        });

        Schema::table('manutenzioni', function (Blueprint $table) {
            $table->unsignedBigInteger('cisterne_id')->after('litri')->nullable();
            $table->foreign('cisterne_id')->references('id')->on('cisterne')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manutenzioni', function (Blueprint $table) {
            $table->dropForeign('cisterne_id');
            $table->dropColumn('cisterne_id');
        });

        Schema::dropIfExists('cisterne');
    }
};
