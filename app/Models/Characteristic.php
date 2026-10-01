<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Characteristic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value','feature_id','varity_id','year','zonaltrial_id','block_id','repeats_values','locality_id'
    ];

    public function varity()
    {   
        return $this->belongsTo(Varity::class, 'varity_id');
    }

    public function block()
    {   
        return $this->belongsTo(Block::class, 'block_id');
    }
}
