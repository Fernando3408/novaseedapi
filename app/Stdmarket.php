<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stdmarket extends Model
{	

	protected $fillable = [
        'name','value','market_id','varities_string'
    ];

    public function market()
    {
        return $this->belongsTo(Market::class,'market_id');
    }
}
