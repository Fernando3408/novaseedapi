<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompareFeatureConfigsTable extends Migration
{
    public function up()
    {
        Schema::create('compare_feature_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->unsignedInteger('user_id')->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->timestamps();
        });

        Schema::create('compare_feature_config_features', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('config_id');
            $table->unsignedInteger('feature_id');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['config_id', 'feature_id'], 'compare_config_feature_unique');
            $table->index('feature_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('compare_feature_config_features');
        Schema::dropIfExists('compare_feature_configs');
    }
}
