<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('key');

            $table->integer('owner')->unsigned()
            ->comment = "the user id of node creator";

            $table->timestamps();

            $table->engine = "InnoDB";
        });

        Schema::create('node_permissions', function (Blueprint $table) {
            $table->integer('node_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('node_id')->references('id')->on('nodes')
            ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('cascade');

            $table->unique(['node_id', 'user_id']);

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
        Schema::drop('node_permissions');
        Schema::drop('nodes');
    }
}
