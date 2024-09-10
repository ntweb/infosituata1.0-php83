<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVersionToInailModot23 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inail_modot23', function (Blueprint $table) {
            $table->uuid('codice_evento')->nullable()->after('id');
            $table->json('content')->nullable()->after('updated_users_id');
            $table->string('version', 10)->default('2023')->after('content');
            $table->string('attivita')->nullable()->after('reparto');

            $table->text('azioni_migl_prev_altro')->nullable()->after('updated_users_id');
            $table->text('azioni_migl_prev_verifica')->nullable()->after('updated_users_id');
            $table->text('azioni_migl_prev_definizione')->nullable()->after('updated_users_id');
            $table->text('azioni_migl_prev_informazione')->nullable()->after('updated_users_id');
            $table->text('azioni_migl_prev_formazione')->nullable()->after('updated_users_id');
            $table->text('azioni_migl_prev_tecnico')->nullable()->after('updated_users_id');
            $table->text('descrizione_finale_evento')->nullable()->after('updated_users_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inail_modot23', function (Blueprint $table) {
            $table->dropColumn('codice_evento');
            $table->dropColumn('content');
            $table->dropColumn('version');

            $table->dropColumn('azioni_migl_prev_altro');
            $table->dropColumn('azioni_migl_prev_verifica');
            $table->dropColumn('azioni_migl_prev_definizione');
            $table->dropColumn('azioni_migl_prev_informazione');
            $table->dropColumn('azioni_migl_prev_formazione');
            $table->dropColumn('azioni_migl_prev_tecnico');
            $table->dropColumn('descrizione_finale_evento');
        });
    }
}
