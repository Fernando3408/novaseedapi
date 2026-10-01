<?php

namespace App\Http\Middleware;

use Closure;
use App\PotatoDevice;

class PotatoDeviceAuth
{
    public function handle($request, Closure $next)
    {
        $deviceId = $request->header('X-Device-Id');
        $token = $request->header('X-Device-Token');

        if (!$deviceId || !$token) {
            return response()->json(array('message' => 'Missing device credentials'), 401);
        }

        $device = PotatoDevice::where('device_id', $deviceId)
            ->where('device_token', $token)
            ->first();

        if (!$device) {
            return response()->json(array('message' => 'Invalid device credentials'), 401);
        }

        $device->last_seen_at = date('Y-m-d H:i:s');
        $device->save();
        $request->attributes->set('potato_device', $device);

        return $next($request);
    }
}

