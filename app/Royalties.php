<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Royalties extends Model
{
    protected $fillable = [
        'year', 'pay', 'season','farmer_id','companies_id'
    ];

    public function farmer()
	{
	  return $this->belongsTo(Models\Farmer::class);
	}

	public function company()
	{
	  return $this->belongsTo(Models\Companies::class);
	}
}
