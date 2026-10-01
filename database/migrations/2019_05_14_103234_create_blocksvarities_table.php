<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksvaritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocksvarities', function (Blueprint $table) {
            $table->increments('id');
            //FK Bloque
            $table->unsignedInteger('block_id')->nullable();
            $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade');
            $table->unsignedInteger('varity_id')->nullable();
            $table->foreign('varity_id')->references('id')->on('varities');
            //posición en el bloque
            $table->string("position")->nullable();
            //tipo de ensayo
            $table->string("type")->default(0);
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
        Schema::dropIfExists('blocksvarities');
    }
}
