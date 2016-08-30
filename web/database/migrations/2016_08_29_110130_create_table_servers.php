<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableServers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')
            ->comment = "server name";
            $table->string('key')
            ->comment = "server key";

            $table->integer('owner')->unsigned()
            ->comment = "server creator";

            $table->timestamps();
            $table->softDeletes();

            $table->engine = "InnoDB";
        });

        Schema::create('server_permissions', function (Blueprint $table) {
            $table->integer('server_id')->unsigned()
            ->comment = "server id";
            $table->integer('user_id')->unsigned()
            ->comment = "user id";
            $table->enum('role', ['manager', 'watcher'])->default('watcher')
            ->comment = "user is a server manager or server watcher";

            $table->foreign('server_id')->references('id')->on('servers')
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
        Schema::drop('servers');
    }
}
