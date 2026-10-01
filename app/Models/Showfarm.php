<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showfarm extends Model
{	

    protected $fillable = [
    	"feature_id","block_id","type"
	];

    public function feature()
    {
        return $this->belongsTo('App\Models\Feature','feature_id');
    }
}
