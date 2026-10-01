<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zonaltrial extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','varity_id','community_id','review'
    ];

    public function community()
	{
	  return $this->belongsTo(Community::class);
	}

	public function varity()
	{
	  return $this->belongsTo(Varity::class);
	}
}
