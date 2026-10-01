<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');
            //¿Qué linea del mapeo ocupa la posición?
            $table->integer("line");
            //FK Mapeo
            $table->unsignedInteger('mapout_id')->nullable();
            $table->foreign('mapout_id')->references('id')->on('mapouts')->onDelete('cascade');
            //FK Variedad
            $table->unsignedInteger('varity_id')->nullable();
            $table->foreign('varity_id')->references('id')->on('varities');
            //$table->unsignedInteger('block_id')->nullable();
            //$table->foreign('block_id')->references('id')->on('blocks');
            $table->text('observation')->nullable();
            //Posición TOP en las lineas.
            $table->double('positionY',8,2)->nullable();
            //Estado del elemento ( 0 => Sin modificar, 1 => Ya se modificó )
            $table->tinyInteger("step")->default(0);
            //contadores de tipos de papas
            $table->integer("red")->default(0);
            $table->integer("redmeat")->default(0);
            $table->integer("blue")->default(0);
            $table->integer("yellow")->default(0);
            $table->integer("elongated")->default(0);
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
        Schema::dropIfExists('positions');
    }
}
