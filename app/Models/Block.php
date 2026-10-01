<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use SoftDeletes;

    protected $fillable = [
        'description','lines','year','latitude','longitude','limit','offline','locality'
    ];

    /*public function crossings()
    {
        return $this->hasMany('App\Models\Varity');
    }*/
}
