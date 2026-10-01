<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComparesTables extends Migration
{
    public function up()
    {
        Schema::create('compares', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->longText('feature_ids')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('compare_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        Schema::create('type_compare', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        Schema::create('compare_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('compare_id');
            $table->integer('rank')->nullable();
            $table->text('description')->nullable();
            $table->text('free_description')->nullable();
            $table->unsignedInteger('id_varity');
            $table->unsignedInteger('id_type_action')->nullable();
            $table->longText('feature_values')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('compare_id');
            $table->index('id_varity');
            $table->index('id_type_action');
            $table->index('description');
        });
    }

    public function down()
    {
        Schema::dropIfExists('compare_details');
        Schema::dropIfExists('type_compare');
        Schema::dropIfExists('compare_descriptions');
        Schema::dropIfExists('compares');
    }
}
