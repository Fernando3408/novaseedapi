<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharacteristics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characteristics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->unsignedInteger('feature_id')->index()->nullable();
            $table->unsignedInteger('varity_id')->index()->nullable();
            $table->unsignedInteger('zonaltrial_id')->index()->nullable();
            $table->unsignedInteger('block_id')->nullable();
            $table->foreign('block_id')->references('id')->on('blocks');
            $table->integer('year');
            $table->timestamps();
        });

        Schema::table('characteristics', function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->foreign('feature_id', 'characteristics_features')
                ->references('id')
                ->on('features')
                ->onDelete('cascade');
        });

        Schema::table('characteristics', function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->foreign('zonaltrial_id', 'characteristics_zonaltrials')
                ->references('id')
                ->on('zonaltrials')
                ->onDelete('cascade');
        });

        Schema::table('characteristics', function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->foreign('varity_id', 'characteritics_varities')
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
        Schema::dropIfExists('characteristics');
    }
}
