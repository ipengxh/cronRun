<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')
            ->comment = "project name";
            $table->string('key')
            ->comment = "project key";
            $table->integer('node_id')->unsigned()
            ->comment = "belongs to node";

            $table->integer('owner')->unsigned()
            ->comment = "project creator";

            $table->timestamps();
            $table->softDeletes();

            $table->engine = "InnoDB";
        });

        Schema::create('project_permissions', function (Blueprint $table) {
            $table->integer('project_id')->unsigned()
            ->comment = "project id";
            $table->integer('user_id')->unsigned()
            ->comment = "user id";
            $table->enum('role', ['manager', 'watcher'])->default('watcher')
            ->comment = "user is a project manager or project watcher";

            $table->unique(['project_id', 'user_id']);

            $table->engine = "InnoDB";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('projects');
        Schema::drop('project_permissions');
    }
}
