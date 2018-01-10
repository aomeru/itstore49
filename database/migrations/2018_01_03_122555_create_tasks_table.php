<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
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

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('inventory_id')->unsigned()->nullable();
            $table->foreign('inventory_id')->references('id')->on('inventories');

            $table->integer('assigned_by')->unsigned();
            $table->foreign('assigned_by')->references('id')->on('users');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('users');

            $table->string('title');

            $table->enum('status', ['opened','unresolved','closed'])->default('opened');

            $table->string('type');

            $table->timestamps();
        });

        DB::update("ALTER TABLE tasks AUTO_INCREMENT = 68655");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
