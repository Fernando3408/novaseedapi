<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Views_model extends Model
{	
	protected $connection = 'mysql_readonly';
    protected $table = 'AVG_CHARACTERISTICS';
    protected $table = 'AVG_CHARACTERISTICS';
    
	protected $fillable = [
        'varity_id', 'VARITY_NAME', 'feature_id','FEATURE_NAME','PROMEDIO','CANTIDAD'
    ];

}
