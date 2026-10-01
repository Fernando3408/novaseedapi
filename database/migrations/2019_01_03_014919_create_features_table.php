<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('namees');
            $table->string('slug');
            $table->string('year');
            $table->integer('type');
            $table->unsignedInteger('typefeature_id')->index()->nullable();
            $table->timestamps();
        });

        Schema::table('features', function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->foreign('typefeature_id', 'features_typefeatures')
                ->references('id')
                ->on('typefeatures')
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
        Schema::dropIfExists('features');
    }
}
