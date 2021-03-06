<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjects extends Migration
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

            $table->string('name');
            $table->string('key');
            $table->integer('node_id')->unsigned()
            ->comment = "belongs to node";

            $table->integer('owner')->unsigned()
            ->comment = "project creator";

            $table->foreign('node_id')->references('id')->on('nodes')
            ->onDelete('cascade');
            $table->foreign('owner')->references('id')->on('users')
            ->onDelete('cascade');

            $table->timestamps();

            $table->engine = "InnoDB";
        });

        Schema::create('project_permissions', function (Blueprint $table) {
            $table->integer('project_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('project_id')->references('id')->on('projects')
            ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('cascade');

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
        Schema::drop('project_permissions');
        Schema::drop('projects');
    }
}
