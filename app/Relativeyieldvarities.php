<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relativeyieldvarities extends Model
{	

    protected $table = 'relative_yields_varities';


	protected $fillable = [
        'id','varity_id','relative_yield_id'
    ];

    public function varity()
    {
        return $this->belongsTo('App\Model\Varity','varity_id');
    }

    public function relativeyield()
    {
        return $this->belongsTo('App\Relativeyield','relative_yield_id');
    }
}
