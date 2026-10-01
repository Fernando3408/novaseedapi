<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    protected $fillable = ['link', 'varity_id','year'];

    protected $table = 'multimedias';

    public function varity()
	{
	  return $this->belongsTo(Varity::class);
	}
}
