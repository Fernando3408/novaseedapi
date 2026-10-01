<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PotatoDevice extends Model
{
    protected $fillable = [
        'device_id',
        'device_token',
        'name',
        'app_version',
        'last_seen_at',
    ];

    protected $hidden = [
        'device_token',
    ];
}

