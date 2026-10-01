<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str as Str;
use Illuminate\Database\Eloquent\SoftDeletes;


class Varity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use SoftDeletes;
    protected $fillable = [
        'mother_id','father_id','code','year','name','berries','crossing','typecrossing','observation','parent_id','user_id','market_id','slug','marketold_id','mapout_id','berries_objective','status','polem_save','glasses','greenhouse_id','potatoes','block_deleted'
    ];

    /*
    public function getNameAttribute($value)
    {
        return "<span style='color: green'>{$this->attributes['name']}</span>";
    }
    */

    public function mother()
    {
        return $this->belongsTo(Varity::class,'mother_id')->withTrashed();
    }

    public function father()
    {
        return $this->belongsTo(Varity::class,'father_id')->withTrashed();
    }

    public function mapout()
    {
        return $this->belongsTo(Mapout::class,'mapout_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function marketold()
    {
        return $this->belongsTo(Market::class,'marketold_id');
    }
    public function market()
    {
        return $this->belongsTo(Market::class,'market_id');
    }
    public function characteristics()
    {
        return $this->hasMany('App\Models\Characteristic');
    }

    public function multimedias()
    {
        return $this->hasMany('App\Models\Multimedia');
    }

    public function observations()
    {
        return $this->hasMany('App\Models\Observation');
    }

    public function binnacles()
    {
        return $this->hasMany('App\Models\Binnacle');
    }

    public function position()
    {
        return $this->hasMany('App\Models\Position','varity_id');
    }

    public function blocksvarity()
    {
        return $this->hasMany('App\Models\Blocksvarity','varity_id');
    }

    public function greenhouse()
    {
        return $this->belongsTo('App\GreenHouse','greenhouse_id');
    }
    
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    public function motherName()
    {
        return $this->parentName('mother', $this->mother_id);
    }

    public function fatherName()
    {
        return $this->parentName('father', $this->father_id);
    }

    private function parentName($relation, $parentId)
    {
        if (empty($parentId)) {
            return "-";
        }

        $parent = $this->relationLoaded($relation) ? $this->{$relation} : null;

        if ($parent === null) {
            $parent = self::withTrashed()->find($parentId);
        }

        if ($parent === null) {
            return "#".$parentId;
        }

        $name = trim((string) @$parent->name);
        if ($name !== '') {
            return $name;
        }

        $code = trim((string) @$parent->code);
        if ($code !== '') {
            return $code;
        }

        return "#".$parentId;
    }

    public function avg_characteristics()
    {
        return $this->hasMany('App\Avg_characteristic');
    }

    public function traceability()
    {
        return $this->hasMany(Traceability::class);
    }

    public function relativeyieldvarities()
    {
        return $this->hasMany(Relativeyieldvarities::class);
    }

    public function getPositionSum(){

        $res = $this->position()->get()->sum( function($p){
            return $p->red + $p->blue + $p->yellow + $p->elongated; 
        });

        return $res;
    }

    public function getEfectivity(){

        $res = 0;
        $colors = $this->getPositionSum();

        if($colors > 0 && $this->potatoes > 0 ){
            $res = round($colors*100/$this->potatoes,2);
        }

        return $res;
    }

    public function outTrazeabilities()
    {
        return $this->belongsToMany(
            \App\Out_trazeabilities::class,
            'out_trazeability_varity',
            'varity_id',
            'out_trazeability_id'
        )->withTimestamps();
    }

    public function getNameAttribute($value)
    {
        // Valor real desde DB (por si $value llega con casts raros)
        $raw = $value ?? ($this->attributes['name'] ?? '');

        // No ensuciar APIs / JSON / consola
        if (app()->runningInConsole()) {
            return $raw;
        }
        if (request() && request()->expectsJson()) {
            return $raw;
        }

        // Determinar si tiene trazabilidad exterior:
        // 1) Si viene precargado por withCount, úsalo (evita N+1)
        if (array_key_exists('out_trazeabilities_count', $this->attributes)) {
            $hasOut = ((int) $this->attributes['out_trazeabilities_count']) > 0;
        } else {
            // 2) Si no, fallback a exists() (puede generar consultas si lo usas en loop)
            $hasOut = $this->outTrazeabilities()->exists();
        }

        if (!$hasOut) {
            return $raw;
        }

        // Icono (LineAwesome / Metronic)
        return $raw . ' <i class="la la-globe text-warning" title="Trazabilidad exterior" style="margin-left:4px;"></i>';
    }

    
}
