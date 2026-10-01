<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('varities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mother_id')->index()->nullable();
            $table->unsignedInteger('father_id')->index()->nullable();
            $table->unsignedInteger('marketold_id')->index()->nullable();
            $table->unsignedInteger('market_id')->index()->nullable();
            $table->string('code')->nullable();
            $table->boolean('crossing')->default(0);
            $table->boolean('typecrossing')->default(0);
            $table->boolean('zona')->default(0);
            $table->integer('year')->nullable();
            $table->integer('berries')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('observation')->nullable();
            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->unsignedInteger('user_id')->index()->nullable();
            //Relación con mapeo básico
            $table->unsignedInteger('mapout_id')->nullable();
            $table->foreign('mapout_id')->references('id')->on('mapouts');
            /*********************/
            //sirve para bloque
            //$table->unsignedInteger('block_id')->nullable();
            //$table->foreign('block_id')->references('id')->on('blocks');
            //posicion (1.1.1) (1.2.1)
            //$table->string("position")->nullable();
            /********************/
            $table->timestamps();
        });

        Schema::table('varities', function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->foreign('market_id', 'markets_varities')
                ->references('id')
                ->on('markets')
                ->onDelete('cascade');
        });

        Schema::table('varities', function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->foreign('user_id', 'users_varities')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('varities');
    }
}
