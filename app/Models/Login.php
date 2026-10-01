<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id'
    ];

    /**
	 * Get the user that owns the phone.
	 */
	public function user()
	{
	    return $this->belongsTo('App\User');
	}
}
