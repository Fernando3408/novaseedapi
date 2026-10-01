<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Fries_avg_characteristics extends Model
{	

	#protected $connection = 'mysql_remote';
    protected $table      = 'fries_avg_characteristics';
	
    public function varity()
    {
        return $this->belongsTo(Varity::class,'varity_id');
    }
}
