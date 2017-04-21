<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTasks extends Migration
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
            $table->string('token', 32);
            $table->integer('project_id')->unsigned()
                ->comment = "belongs to project";

            $table->integer('owner')->unsigned()
                ->comment = "task creator";

            $table->string('command');
            $table->string('param')->nullable();
            $table->unsignedInteger('interval')->nullable();
            $table->string('timer')->nullable();
            $table->unsignedInteger('min_time')->default(0);
            $table->unsignedInteger('max_time')->default(3600);
            $table->unsignedInteger('timeout')->default(86400);
            $table->unsignedInteger('retry_times')->default(0);
            $table->unsignedInteger('retry_interval')->default(0);
            $table->string('error_code')->nullable();
            $table->boolean('notify_enabled')->default(true);
            $table->string('notify_email')->nullable();
            $table->boolean('notify_success')->default(false);
            $table->boolean('notify_fail')->default(true);
            $table->boolean('enabled')->default(true);

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
