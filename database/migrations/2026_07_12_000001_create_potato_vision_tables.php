<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePotatoVisionTables extends Migration
{
    public function up()
    {
        Schema::create('potato_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('device_id', 120)->unique();
            $table->string('device_token', 96);
            $table->string('name')->nullable();
            $table->string('app_version')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });

        Schema::create('potato_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('device_id')->index();
            $table->string('lot_code')->nullable();
            $table->string('line_name')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('potato_detections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('session_id')->index();
            $table->string('event_id', 120)->unique();
            $table->integer('tracked_id')->default(0);
            $table->string('captured_at')->nullable();
            $table->string('color_class', 24)->default('unknown');
            $table->decimal('anomaly_score', 6, 4)->default(0);
            $table->decimal('area_px', 12, 2)->default(0);
            $table->decimal('center_x', 10, 2)->default(0);
            $table->decimal('center_y', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('potato_detections');
        Schema::dropIfExists('potato_sessions');
        Schema::dropIfExists('potato_devices');
    }
}

