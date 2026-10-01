<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Traceability extends Model
{
    protected $fillable = [
        'farmer_id', 'land_id', 'client_id','varity_id','quantity','production','certifies_id','provider'
    ];

    public function varity()
	{
	  return $this->belongsTo(Models\Varity::class);
	}

	public function land()
	{
	  return $this->belongsTo(Land::class);
	}

	public function farmer()
	{
	  return $this->belongsTo(Farmer::class);
	}

	public function client()
	{
	  return $this->belongsTo(Client::class);
	}

	
}
