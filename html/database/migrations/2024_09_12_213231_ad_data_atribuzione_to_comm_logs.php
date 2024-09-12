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
        Schema::table('commesse_log', function (Blueprint $table) {
            $table->date('data_attribuzione')->nullable()->after('stato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commesse_log', function (Blueprint $table) {
            $table->dropColumn('data_attribuzione');
        });
    }
};
