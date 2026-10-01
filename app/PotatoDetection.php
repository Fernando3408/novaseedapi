<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PotatoDetection extends Model
{
    protected $fillable = [
        'session_id',
        'event_id',
        'tracked_id',
        'captured_at',
        'color_class',
        'anomaly_score',
        'area_px',
        'center_x',
        'center_y',
    ];
}

