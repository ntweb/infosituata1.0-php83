<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAutrizzazioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autorizzazioni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->string('module', 50)->index();
            $table->string('reference_controller', 50)->nullable();
            $table->string('permission', 50)->index();
            $table->json('gruppi_ids')->nullable();
            $table->timestamps();

            $table->unique(['azienda_id', 'permission']);
        });

        $created_at = \Carbon\Carbon::now();

        $auths = \Illuminate\Support\Facades\DB::table('checklists_autorizzazioni')->get();
        foreach ($auths as $auth) {
            \Illuminate\Support\Facades\DB::table('autorizzazioni')->insert([
                'azienda_id' => $auth->azienda_id,
                'module' => 'checklist',
                'reference_controller' => $auth->reference_controller,
                'permission' => 'can_create_checklist_'.$auth->reference_controller,
                'gruppi_ids' => $auth->gruppi_ids,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ]);
        }

        $auths = \Illuminate\Support\Facades\DB::table('rapportini_autorizzazioni')->get();
        foreach ($auths as $auth) {
            \Illuminate\Support\Facades\DB::table('autorizzazioni')->insert([
                'azienda_id' => $auth->azienda_id,
                'module' => 'rapportini',
                'reference_controller' => $auth->controller,
                'permission' => 'can_create_rapportini_'.$auth->controller,
                'gruppi_ids' => $auth->gruppi_ids,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ]);
        }

        Schema::dropIfExists('checklists_autorizzazioni');
        Schema::dropIfExists('rapportini_autorizzazioni');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autorizzazioni');
    }
}
