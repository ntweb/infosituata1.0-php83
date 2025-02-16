<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('aziende', function (Blueprint $table) {
            $table->enum('module_tasks', ['0', '1'])->default('0')->after('module_whatsapp_tel');
        });

        Schema::create('tasks_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('azienda_id');
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();

            // $table->nestedSet();
            $table->unsignedInteger('_lft');
            $table->unsignedInteger('_rgt');
            $table->uuid('parent_id')->nullable();

        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('azienda_id');
            $table->uuid('clienti_id')->nullable();
            $table->string('label');
            $table->text('description')->nullable();
            $table->string('tags')->nullable();
            $table->json('users_ids')->nullable();
            $table->uuid('tasks_template_id')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->uuid('root_id')->nullable();
            $table->timestamps();

            // $table->nestedSet();
            $table->unsignedInteger('_lft');
            $table->unsignedInteger('_rgt');
            $table->uuid('parent_id')->nullable();

            $table->foreign('tasks_template_id')->references('id')->on('tasks_templates')->onDelete('set null');
            $table->foreign('clienti_id')->references('id')->on('clienti')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aziende', function (Blueprint $table) {
            $table->dropColumn('module_tasks');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_clienti_id_foreign');
            $table->dropForeign('tasks_tasks_template_id_foreign');
        });

        Schema::dropIfExists('tasks_templates');
        Schema::dropIfExists('tasks');
    }
}
