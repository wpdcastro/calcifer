<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSended extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sended', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->date("date");
            $bp->integer('user_id')->unsigned();
            $bp->foreign('user_id')->references('id')->on('user');
            $bp->integer('notification_id')->unsigned();
            $bp->foreign('notification_id')->references('id')->on('notification');
            $table->foreign("notification_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sended');
    }
}
