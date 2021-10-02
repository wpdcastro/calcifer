<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fire', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("latitude");
            $table->string("longitude");
            $table->string("city");
            $table->string("state");
            $table->string("country");
            $table->string("phone_area");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fire');
    }
}
