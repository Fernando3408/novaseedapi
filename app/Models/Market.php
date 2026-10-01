<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    public function varities()
    {
        return $this->hasMany('App\Models\Varity');
    }

    public function relativeyield()
    {
        return $this->hasMany(Relativeyield::class);
    }
}
