<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GreenHouse extends Model
{
    protected $table = 'greenhouse';
    protected $fillable = [
        'name'
    ];

    public function characteristic()
    {   
        return $this->hasMany('App\Models\Varity','greenhouse_id');
    }
}
