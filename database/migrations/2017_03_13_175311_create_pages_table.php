<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->integer('type_id')->default(0);
			$table->string('slug',150)->unique();
			$table->string('icon');
			$table->enum('type', ['page', 'subpage'])->default('page');
			$table->timestamps();
        });
		
		DB::update("ALTER TABLE pages AUTO_INCREMENT = 512");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
