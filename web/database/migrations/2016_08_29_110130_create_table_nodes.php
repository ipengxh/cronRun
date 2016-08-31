<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNodes extends Migration
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

            $table->string('name')
            ->comment = "node name";
            $table->string('key')
            ->comment = "node key";

            $table->integer('owner')->unsigned()
            ->comment = "node creator";

            $table->timestamps();
            $table->softDeletes();

            $table->engine = "InnoDB";
        });

        Schema::create('node_permissions', function (Blueprint $table) {
            $table->integer('node_id')->unsigned()
            ->comment = "node id";
            $table->integer('user_id')->unsigned()
            ->comment = "user id";
            $table->enum('role', ['manager', 'watcher'])->default('watcher')
            ->comment = "user is a node manager or node watcher";

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
        Schema::drop('nodes');
        Schema::drop('node_permissions');
    }
}
