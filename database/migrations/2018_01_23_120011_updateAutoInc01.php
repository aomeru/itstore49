<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAutoInc01 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::update("ALTER TABLE purchases AUTO_INCREMENT = 68655");
        DB::update("ALTER TABLE plogs AUTO_INCREMENT = 25562");
        DB::update("ALTER TABLE ilogs AUTO_INCREMENT = 12515");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
