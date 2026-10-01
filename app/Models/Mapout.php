<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapout extends Model
{
    protected $fillable = ['lines', 'year','state','greenhouse_id','description'];

    protected $table = 'mapouts';

    public function positions()
    {
        return $this->hasMany('App\Models\Position');
    }

    public function coded()
    {
        return $this->hasMany('App\Models\Varity');
    }

     public function greenhouse()
    {
        return $this->belongsTo('App\GreenHouse');
    }
}
