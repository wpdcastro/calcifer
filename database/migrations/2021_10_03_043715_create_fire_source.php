<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFireSource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fire_source', function (Blueprint $table) {
            $table->bigIncrements("id")->unsigned();
            $table->string("latitude");
            $table->string("longitude");
            $table->string("satellite");
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
        Schema::dropIfExists('fire_source');
    }
}
