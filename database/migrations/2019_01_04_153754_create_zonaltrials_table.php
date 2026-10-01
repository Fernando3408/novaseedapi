<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZonaltrialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zonaltrials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('review');
            $table->unsignedInteger('varity_id')->index()->nullable();
            $table->unsignedInteger('community_id')->index()->nullable();
            $table->timestamps();
        });

        Schema::table('zonaltrials', function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->foreign('varity_id', 'zonaltrials_varities')
                ->references('id')
                ->on('varities')
                ->onDelete('cascade');
        });

        Schema::table('zonaltrials', function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->foreign('community_id', 'zonaltrials_communities')
                ->references('id')
                ->on('communities')
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
        Schema::dropIfExists('zonaltrials');
    }
}
