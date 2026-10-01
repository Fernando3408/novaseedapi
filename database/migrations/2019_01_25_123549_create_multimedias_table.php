<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultimediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multimedias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('link');
            $table->integer('year');
            $table->unsignedInteger('varity_id')->index()->nullable();
            $table->timestamps();
        });

        Schema::table('multimedias', function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->foreign('varity_id', 'multimedias_varities')
                ->references('id')
                ->on('varities')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('multimedias');
    }
}
