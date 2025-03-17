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
        Schema::create('cisterne_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cisterne_id');
            $table->decimal('litri', 10, 2);
            $table->decimal('prezzo', 10, 2);
            $table->timestamps();

            $table->foreign('cisterne_id')->references('id')->on('cisterne')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cisterne_log');
    }
};
