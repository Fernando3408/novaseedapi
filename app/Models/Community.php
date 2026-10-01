<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
	protected $table = 'communities';

    public function province()
	{
		return $this->belongsTo('App\Models\Province');
	}

	public function schools()
	{
		return $this->hasMany('App\Models\School');
	}

	public function land()
	{
		return $this->hasMany('App\Land');
	}
}
