<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fries_characteristics extends Model
{
    protected $table = 'fries_characteristics';

    protected $fillable = [
        'enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','characteristic_id','block_id','locality_id'
    ];

    public function characteristic()
    {   
        return $this->belongsTo('App\Models\Characteristic', 'characteristic_id');
    }
}
