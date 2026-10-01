<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Showfarm;

class Feature extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','year','type','slug','repeat_do','frying_test','typefeature_id','localities_viewer'
    ];

    public function values()
    {
        return $this->hasMany('App\Models\Value','feature_id');
    }

    public function typefeature()
    {
        return $this->belongsTo(Typefeature::class,'typefeature_id');
    }

    public function showFarm()
    {
        return $this->hasMany('App\Models\Showfarm','feature_id');
    }

    public function getShowFarm($block_id=null,$type=null)
    {
        $feature_id = $this->id;

        if(Showfarm::where('block_id',$block_id)->where('feature_id',$feature_id)->where('type',$type)->count() > 0){
            return true;
        }
        
        return false;
    }

    public function getLocal(){
        return Feature::class;
    }

    
}
