<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ht_result extends Model
{
    protected $table = 'ht_results';
    public $timestamps = false;
    protected $fillable = ['team1','player1','p1','team2','player2','p2','fecha','hora','ht1','ht2','ft1','ft2','spread'];

    /**
     * Scope Mágico: Calcula porcentajes y conteos directamente en la BD.
     * Evita divisiones por cero con NULLIF.
     */
    public function scopeSelectStats($query)
    {
        // Generamos dinámicamente las columnas para 0.5 a 6.5
        $selects = [
            DB::raw('COUNT(*) as partidos_jugados'),
        ];

        // Porcentajes de Goles Totales (Over)
        foreach (range(0, 6) as $i) {
            // Ejemplo: (SUM(...) / Total) * 100
            $selects[] = DB::raw("ROUND(SUM(CASE WHEN (ft1 + ft2) > $i.5 THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(*), 0)) as pct_{$i}5");
        }

        // Porcentajes de Spread (SP)
        foreach (range(0, 6) as $i) {
            // Asumiendo que tu columna 'spread' ya tiene el cálculo o usas lógica dinámica
            // Si spread es calculado al vuelo: ( (ft1+ft2) - (ht1+ht2) )
            $selects[] = DB::raw("ROUND(SUM(CASE WHEN spread > $i.5 THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(*), 0)) as sp_{$i}5");
        }
        
        // Agregamos SP exacto (SP 0, SP 1...) si lo necesitas para la tabla principal
         foreach (range(0, 5) as $i) {
            $selects[] = DB::raw("ROUND(SUM(CASE WHEN spread = $i THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(*), 0)) as sp_exact_{$i}");
        }

        return $query->addSelect($selects);
    }
    
    // Mantenemos tu helper estático
    public static function getTeamOptions(): array
    {
        $teams = DB::table((new self)->getTable())->pluck('team1')
            ->merge(DB::table((new self)->getTable())->pluck('team2'))
            ->unique()->filter()->values();
            
        $options = [];
        foreach ($teams as $t) {
            $options[str_replace([' ', '.'], '', $t)] = $t;
        }
        return $options;
    }
}