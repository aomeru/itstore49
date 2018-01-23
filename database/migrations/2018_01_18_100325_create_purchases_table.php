<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('title');

            $table->string('po')->nullable();
            $table->string('dn')->nullable();
            $table->string('inv')->nullable();

            $table->integer('total')->nullable();

            $table->timestamps();

            $table->timestamp('po_at')->nullable();
            $table->timestamp('dn_at')->nullable();
            $table->timestamp('inv_at')->nullable();
        });

        DB::update("ALTER TABLE purchases AUTO_INCREMENT = 68655");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
