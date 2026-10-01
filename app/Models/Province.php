<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
	protected $table = 'provinces';

    public function region()
	{
		return $this->belongsTo('App\Models\Region');
	}

	public function communities()
	{
		return $this->hasMany('App\Models\Community');
	}
}
