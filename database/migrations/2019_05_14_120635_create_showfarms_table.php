<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowfarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('showfarms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('feature_id')->nullable();
            $table->foreign('feature_id')->references('id')->on('features')->onDelete('cascade');
            $table->unsignedInteger('block_id')->nullable();
            $table->foreign('block_id')->references('id')->on('blocks');
            $table->string("type")->nullable();
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
        Schema::dropIfExists('showfarms');
    }
}
