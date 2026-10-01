<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $fillable = [
        'name', 'email', 'phone','rut','description','latitude','longitude', 'community_id'
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

	public function royalties()
	{
		return $this->hasMany(Royalties::class);
	}

	public function getStatusPayment(){
		$year = date("Y");
		$d = $this->royalties()->where('year', $year)->first();

		!empty($d) ? $status = true : $status = false;

		return $status;
	}

}
