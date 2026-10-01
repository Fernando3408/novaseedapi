<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
	    'name',
	    'group_p',
	    'status',
	    'id_externo',
	];
}
