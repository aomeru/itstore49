<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

			$table->integer('role_id')->unsigned();
			$table->foreign('role_id')->references('id')->on('roles');

            $table->integer('unit_id')->unsigned()->nullable();
			$table->foreign('unit_id')->references('id')->on('units');

            $table->string('email',150)->unique()->nullable();

            $table->string('username', 50)->unique();

            $table->string('password');

			$table->string('firstname');
			$table->string('lastname');
			$table->enum('gender',['male','female']);

            $table->string('staff_id',20)->unique()->nullable();

			$table->enum('status', ['inactive', 'active', 'blocked'])->default('inactive');

            $table->rememberToken();
            $table->timestamps();
        });

		DB::update("ALTER TABLE users AUTO_INCREMENT = 56986");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
