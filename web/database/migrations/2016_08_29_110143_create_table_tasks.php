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

            $table->string('name')
            ->comment = "task name";
            $table->string('key')
            ->comment = "task key";

            $table->integer('owner')->unsigned()
            ->comment = "task creator";

            $table->timestamps();
            $table->softDeletes();

            $table->engine = "InnoDB";
        });

        Schema::create('task_permissions', function (Blueprint $table) {
            $table->integer('task_id')->unsigned()
            ->comment = "task id";
            $table->integer('user_id')->unsigned()
            ->comment = "user id";
            $table->enum('role', ['manager', 'watcher'])->default('watcher')
            ->comment = "user is a task manager or task watcher";

            $table->foreign('task_id')->references('id')->on('tasks')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');

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
        Schema::drop('tasks');
    }
}
