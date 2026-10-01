<?php

/* modelo para tabla roles de base de datos */


namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
   	
   	public function users()
	{
	  return $this->belongsToMany(User::class);
	}
}
