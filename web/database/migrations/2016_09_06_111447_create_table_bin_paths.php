<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBinPaths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bin_paths', function (Blueprint $table) {
            $table->integer('node_id')->unsigned();
            $table->string('name')
            ->comment = "bin path name";
            $table->string('path')
            ->comment = "bin path";

            $table->foreign('node_id')->references('id')->on('nodes')
            ->onDelete('cascade');

            $table->unique(['node_id', 'path']);

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
        Schema::drop('bin_paths');
    }
}
