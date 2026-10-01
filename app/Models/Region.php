<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /**
	* The attributes that should be mutated to dates.
	*
	* @var array
	*/
   
 	protected $table = 'regions';

	protected $primaryKey = 'id';

	protected $fillable = [
		'name',
		'created_at',
		'updated_at'
	];

	
	public function provinces()
	{
		return $this->hasMany('App\Models\Province');
	}
   
	
}
