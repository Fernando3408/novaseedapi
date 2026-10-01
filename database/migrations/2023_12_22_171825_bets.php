<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Bets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->increments('id');
            $table->string("team1")->nullable();
            $table->string("player1")->nullable();
            $table->string("team2")->nullable();
            $table->string("player2")->nullable();
            $table->string("fecha")->nullable();
            $table->string("hora")->nullable();
            $table->string("avg")->nullable();
            $table->string("finals1")->nullable();
            $table->string("finals2")->nullable();
            $table->string("expected_l")->nullable();
            $table->string("expected_v")->nullable();
            $table->timestamps();
        });
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
