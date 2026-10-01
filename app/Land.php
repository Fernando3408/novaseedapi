<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    protected $fillable = [
        'name', 'latitude','longitude','community_id','farmer_id'
    ];

    public function community()
	{
	  return $this->belongsTo(Models\Community::class);
	}

	public function traceability()
	{
		return $this->hasMany(Traceability::class);
	}

	public function farmer()
	{
	  return $this->belongsTo(Farmer::class);
	}
}
