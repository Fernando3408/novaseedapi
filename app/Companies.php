<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
     protected $fillable = [
        'name', 'mail', 'country','locality','contact','description'
    ];

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
