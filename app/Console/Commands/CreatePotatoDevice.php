<?php

namespace App\Console\Commands;

use App\PotatoDevice;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreatePotatoDevice extends Command
{
    protected $signature = 'potato:device {device_id} {--name=} {--app-version=}';

    protected $description = 'Create or rotate a Potato Vision device token';

    public function handle()
    {
        $deviceId = $this->argument('device_id');
        $token = Str::random(48);
        $device = PotatoDevice::where('device_id', $deviceId)->first();

        if (!$device) {
            $device = new PotatoDevice();
            $device->device_id = $deviceId;
        }

        $device->device_token = $token;
        $device->name = $this->option('name');
        $device->app_version = $this->option('app-version');
        $device->last_seen_at = date('Y-m-d H:i:s');
        $device->save();

        $this->info('Device ID: '.$device->device_id);
        $this->info('Device Token: '.$token);

        return 0;
    }
}

