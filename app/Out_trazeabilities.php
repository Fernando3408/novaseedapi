<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Out_trazeabilities extends Model
{
    protected $table = 'out_trazeabilities';

    // Como created_at / updated_at son int(11) (unix timestamp)
    public $timestamps = true;

    protected $fillable = [
        'agreement_year',
        'country',
        'import_permit',
        'varity_id',
        'client',
    ];

    // Relaciones (ajusta namespace si tus modelos están en App\Models\*)
    public function varity()
    {
        return $this->belongsTo(\App\Models\Varity::class, 'varity_id');
    }

    public function varities(){
        return $this->belongsToMany(
            \App\Models\Varity::class,
            'out_trazeability_varity',
            'out_trazeability_id',
            'varity_id'
        )->withTimestamps();
    }
}
