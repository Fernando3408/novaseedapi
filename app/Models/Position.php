<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
     protected $fillable = [
		'line',
		'mapout_id',
		'varity_id',
		'block_id',
		'observation',
		'red',
		'redmeat',
		'blue',
		'yellow',
		'elongated',
		'step',
		'positionY',
		'flowers',
		'flowering',
		'abort',
		'polem',
		'polem_volume',
		'steril_mom'
	];

	protected $table = 'positions';


	public function crossing()
    {
        return $this->belongsTo(Varity::class,'varity_id');
    }
}
