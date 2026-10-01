<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relativeyield extends Model
{	

    protected $table = 'relative_yields';
	
	protected $fillable = [
       'std','name','market_id'
    ];

    
     public function relativeyieldvarities()
    {
        return $this->hasMany(Relativeyieldvarities::class, 'relative_yield_id');
    }

     public function market()
    {
        return $this->belongsTo('App\Models\market');
    }
}
