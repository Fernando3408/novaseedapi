<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avg_characteristic extends Model
{	

	public function varity()
    {
        return $this->belongsTo(Varity::class,'varity_id');
    }

    
}
