<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('key');
            $table->integer('project_id')->unsigned()
            ->comment = "belongs to project";

            $table->integer('owner')->unsigned()
            ->comment = "task creator";

            $table->foreign('project_id')->references('id')->on('projects')
            ->onDelete('cascade');
            $table->foreign('owner')->references('id')->on('users')
            ->onDelete('cascade');

            $table->timestamps();

            $table->engine = "InnoDB";
        });

        Schema::create('task_permissions', function (Blueprint $table) {
            $table->integer('task_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('task_id')->references('id')->on('tasks')
            ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('cascade');

            $table->unique(['task_id', 'user_id']);

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
        Schema::drop('task_permissions');
        Schema::drop('tasks');
    }
}
