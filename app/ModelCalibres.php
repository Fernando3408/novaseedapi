<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelCalibres extends Model
{
    protected $table = 'model_calibres';
    protected $fillable = [
       'feature_id','year','block_id','papas','varity_id','repeat_nro','value_calib'
    ];
}
