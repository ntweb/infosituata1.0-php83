<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('azienda_id');
            $table->string('key');
            $table->string('label')->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->string('value')->nullable();
            $table->enum('required', ['0', '1'])->default('0');
            $table->json('modules_enabled')->nullable();

            $table->timestamps();

            // $table->nestedSet();
            NestedSet::columns($table);
            $table->unsignedBigInteger('root_id')->nullable();

            $table->unique(['azienda_id', 'key']);
        });

        Schema::create('checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('checklists_templates_id')->nullable();
            $table->unsignedBigInteger('azienda_id');
            $table->string('key');
            $table->string('label')->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->string('value')->nullable();
            $table->enum('required', ['0', '1'])->default('0');
            $table->timestamps();

            // $table->nestedSet();
            NestedSet::columns($table);
            $table->unsignedBigInteger('root_id')->nullable();

            $table->foreign('checklists_templates_id')->references('id')->on('checklists_templates')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('checklists_templates');
        Schema::dropIfExists('checklists');
    }
}
