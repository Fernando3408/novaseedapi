<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blocksvarity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'blocksvarities';

    protected $fillable = [
        'varity_id','block_id','position','type','last_position','review','observation','state'
    ];

    public function varity()
    {   
        return $this->belongsTo(Varity::class, 'varity_id');
    }

     public function varity_destroied()
    {   
        return $this->belongsTo(Varity::class, 'varity_id')->onlyTrashed();
    }

    public function block()
    {   
        return $this->belongsTo(Block::class, 'block_id');
    }
}
