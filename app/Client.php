<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name','rut','observation','community_id'
    ];

    public function land()
	{
		return $this->hasMany(land::class);
	}

	public function traceability()
	{
		return $this->hasMany(Traceability::class);
	}

	public function community()
	{
	  return $this->belongsTo(Models\Community::class);
	}
}
