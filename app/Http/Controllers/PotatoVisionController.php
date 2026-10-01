<?php

namespace App\Http\Controllers;

use App\PotatoDetection;
use App\PotatoDevice;
use App\PotatoSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PotatoVisionController extends Controller
{
    public function registerDevice(Request $request)
    {
        $this->validate($request, array(
            'device_id' => 'required|string|max:120',
        ));

        $token = Str::random(48);
        $device = PotatoDevice::where('device_id', $request->input('device_id'))->first();
        if (!$device) {
            $device = new PotatoDevice();
            $device->device_id = $request->input('device_id');
        }

        $device->device_token = $token;
        $device->name = $request->input('name');
        $device->app_version = $request->input('app_version');
        $device->last_seen_at = date('Y-m-d H:i:s');
        $device->save();

        return response()->json(array(
            'device_id' => $device->device_id,
            'device_token' => $token,
        ), 201);
    }

    public function startSession(Request $request)
    {
        $device = $request->attributes->get('potato_device');

        $session = PotatoSession::create(array(
            'device_id' => $device->id,
            'lot_code' => $request->input('lot_code'),
            'line_name' => $request->input('line_name'),
            'started_at' => $request->input('started_at', date('Y-m-d H:i:s')),
        ));

        return response()->json(array('session_id' => $session->id), 201);
    }

    public function finishSession(Request $request, $session)
    {
        $device = $request->attributes->get('potato_device');
        $potatoSession = PotatoSession::where('id', $session)
            ->where('device_id', $device->id)
            ->firstOrFail();
        $potatoSession->finished_at = date('Y-m-d H:i:s');
        $potatoSession->save();

        return response()->json(array('ok' => true));
    }

    public function storeBatch(Request $request)
    {
        $this->validate($request, array(
            'session_id' => 'required',
            'events' => 'required|array',
        ));

        $sessionId = $request->input('session_id');
        $inserted = 0;
        $duplicates = 0;

        foreach ($request->input('events') as $event) {
            if (!isset($event['event_id'])) {
                continue;
            }

            $exists = PotatoDetection::where('event_id', $event['event_id'])->exists();
            if ($exists) {
                $duplicates++;
                continue;
            }

            PotatoDetection::create(array(
                'session_id' => $sessionId,
                'event_id' => $event['event_id'],
                'tracked_id' => isset($event['tracked_id']) ? $event['tracked_id'] : 0,
                'captured_at' => isset($event['captured_at']) ? $event['captured_at'] : date('Y-m-d H:i:s'),
                'color_class' => isset($event['color_class']) ? $event['color_class'] : 'unknown',
                'anomaly_score' => $this->decimalValue($event, 'anomaly_score', 4),
                'area_px' => $this->decimalValue($event, 'area_px', 2),
                'center_x' => $this->decimalValue($event, 'center_x', 2),
                'center_y' => $this->decimalValue($event, 'center_y', 2),
            ));
            $inserted++;
        }

        return response()->json(array(
            'inserted' => $inserted,
            'duplicates' => $duplicates,
        ));
    }

    public function metrics($session)
    {
        return response()->json($this->metricsPayload($session));
    }

    protected function decimalValue(array $event, $key, $scale)
    {
        if (!isset($event[$key])) {
            return number_format(0, $scale, '.', '');
        }

        return number_format((float) $event[$key], $scale, '.', '');
    }

    public function dashboard(Request $request)
    {
        $sessionId = $request->input('session_id');
        $sessions = PotatoSession::orderBy('id', 'desc')->limit(25)->get();

        return view('potato_vision.dashboard', array(
            'sessionId' => $sessionId,
            'sessions' => $sessions,
        ));
    }

    public function dashboardMetrics($session)
    {
        return response()->json($this->metricsPayload($session));
    }

    protected function metricsPayload($session)
    {
        $query = PotatoDetection::where('session_id', $session);
        $total = (clone $query)->count();
        $anomalies = (clone $query)->where('anomaly_score', '>=', '0.5500')->count();
        $first = (clone $query)->orderBy('created_at', 'asc')->first();
        $last = (clone $query)->orderBy('created_at', 'desc')->first();
        $minutes = 0;

        if ($first && $last) {
            $seconds = max(1, strtotime($last->created_at) - strtotime($first->created_at));
            $minutes = $seconds / 60;
        }

        $colors = (clone $query)
            ->select('color_class', DB::raw('count(*) as total'))
            ->groupBy('color_class')
            ->get();

        $timeline = PotatoDetection::where('session_id', $session)
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:00') as minute"), DB::raw('count(*) as total'))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:00')"))
            ->orderBy('minute', 'asc')
            ->get();

        $potatoSession = PotatoSession::find($session);
        $device = $potatoSession ? PotatoDevice::find($potatoSession->device_id) : null;

        return array(
            'session_id' => $session,
            'total' => $total,
            'potatoes_per_minute' => $minutes > 0 ? round($total / $minutes, 2) : $total,
            'device_status' => $device && $device->last_seen_at ? 'online' : 'unknown',
            'last_connection' => $device ? $device->last_seen_at : null,
            'colors' => $colors,
            'anomalies' => $anomalies,
            'anomaly_percent' => $total > 0 ? round($anomalies * 100 / $total, 2) : 0,
            'timeline' => $timeline,
            'recent_events' => (clone $query)->orderBy('created_at', 'desc')->limit(20)->get(),
        );
    }
}
