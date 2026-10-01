<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PotatoSession extends Model
{
    protected $fillable = [
        'device_id',
        'lot_code',
        'line_name',
        'started_at',
        'finished_at',
    ];
}

