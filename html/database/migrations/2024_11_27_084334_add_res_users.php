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
        Schema::table('items', function (Blueprint $table) {
            $table->string('user_titolo_studio')->after('extras10')->nullable();
            $table->string('user_qualifica_assunzione')->after('extras10')->nullable();
            $table->date('user_data_assunzione')->after('extras10')->nullable();
            $table->string('user_cellulare')->after('extras10')->nullable();
            $table->string('user_telefono')->after('extras10')->nullable();
            $table->string('user_via_residenza')->after('extras10')->nullable();
            $table->string('user_luogo_residenza')->after('extras10')->nullable();
            $table->date('user_data_nascita')->after('extras10')->nullable();
            $table->string('user_luogo_nascita')->after('extras10')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('user_titolo_studio');
            $table->dropColumn('user_qualifica_assunzione');
            $table->dropColumn('user_data_assunzione');
            $table->dropColumn('user_cellulare');
            $table->dropColumn('user_telefono');
            $table->dropColumn('user_via_residenza');
            $table->dropColumn('user_luogo_residenza');
            $table->dropColumn('user_data_nascita');
            $table->dropColumn('user_luogo_nascita');
        });
    }
};
