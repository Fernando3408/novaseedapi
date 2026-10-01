<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typefeature extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','names'
    ];

    public function features()
    {
        return $this->hasMany('App\Models\Feature')->where('status',1);
    }
}
