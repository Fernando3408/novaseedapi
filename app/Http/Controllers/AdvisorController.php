<?php
// app/Http/Controllers/AdvisorController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdvisorService;

class AdvisorController extends Controller
{
    private function asPct($x){
        if ($x === null) return null;
        if ($x <= 1) return round($x*100);
        return round($x);
    }

    private function weightedAvg(array $rows, string $field): ?float {
        $num = 0.0; $den = 0.0;
        foreach ($rows as $r){
            $n = (float)($r['matches'] ?? 0);
            if ($n <= 0) continue;
            $v = $r[$field] ?? null;
            if ($v === null) continue;
            $vp = ($v > 1 ? $v/100.0 : $v);
            $num += $n * $vp;
            $den += $n;
        }
        if ($den <= 0) return null;
        return $num / $den; // 0..1
    }

    private function weightedCount(array $rows): int {
        $s = 0;
        foreach ($rows as $r) {
            $n = (int)($r['matches'] ?? 0);
            if ($n > 0) $s += $n;
        }
        return $s;
    }

    private function bestFieldForSense(string $sentido): string {
        return ($sentido === 'OVER') ? 'market_over' : 'market_under';
    }

    private function pickBestWindows(array $tabla, string $sentido, $minN=8){
        $field = $this->bestFieldForSense($sentido);
        $best = [];
        foreach ($tabla as $row){
            $n = isset($row['matches']) ? (int)$row['matches'] : 0;
            $p = isset($row[$field]) ? (float)$row[$field] : null;
            if ($p !== null && $n >= $minN){
                $best[] = ['window'=>$row['window'], 'p'=>$p, 'n'=>$n];
            }
        }
        usort($best, function($a,$b){
            if ($a['p'] === $b['p']) return $b['n'] <=> $a['n'];
            return $b['p'] <=> $a['p'];
        });
        return array_slice($best, 0, 3);
    }

    private function detectConflicts(array $tablaHt, array $tablaG, string $sentido){
        $field = $this->bestFieldForSense($sentido);
        $byWin = [];
        foreach ($tablaHt as $r){ $byWin[$r['window']]['ht'] = $r; }
        foreach ($tablaG  as $r){ $byWin[$r['window']]['g']  = $r; }
        $conf = [];
        foreach ($byWin as $w=>$pair){
            $a = isset($pair['ht'][$field]) ? (float)$pair['ht'][$field] : null;
            $b = isset($pair['g'][$field])  ? (float)$pair['g'][$field]  : null;
            if ($a!==null && $b!==null){
                $ap = ($a>1 ? $a/100.0 : $a);
                $bp = ($b>1 ? $b/100.0 : $b);
                $gap = abs($ap - $bp) * 100;
                if ($gap >= 12){
                    $conf[] = [
                        'w'=>$w,'gap'=>$gap,
                        'pHt'=>$this->asPct($ap),'pG'=>$this->asPct($bp),
                        'nHt'=>($pair['ht']['matches']??null),
                        'nG'=>($pair['g']['matches']??null)
                    ];
                }
            }
        }
        return $conf;
    }

    // --- EN AdvisorController ---
// Reemplaza el bloque SP dentro de fallbackExplain() por este:

private function fallbackExplain(array $payload, array $baseline, string $sentido): array
{
    $tablaHt = $payload['tabla_ht_resumen'] ?? [];
    $tablaG  = $payload['tabla_global_resumen'] ?? [];
    $razones = [];
    $riesgos = [];

    $topHt = $this->pickBestWindows($tablaHt, $sentido);
    $topG  = $this->pickBestWindows($tablaG,  $sentido);

    foreach ($topHt as $r){
        $razones[] = sprintf("%s HT: %s=%d%% n=%d",
            strtoupper($r['window']),
            $sentido, $this->asPct($r['p']), (int)$r['n']);
    }
    foreach ($topG as $r){
        $razones[] = sprintf("%s Global: %s=%d%% n=%d",
            strtoupper($r['window']),
            $sentido, $this->asPct($r['p']), (int)$r['n']);
    }

    $razones[] = sprintf("Baseline (%s) mezcla HT/Global: p=%.2f, confianza=%.2f",
        $sentido, $baseline['prob'] ?? 0, $baseline['confianza'] ?? 0);

    // Conflictos HT vs Global
    $conf = $this->detectConflicts($tablaHt, $tablaG, $sentido);
    if (!empty($conf)){
        $c = $conf[0];
        $riesgos[] = sprintf("Desacuerdo HT vs Global en %s (gap %dpp: %d%% vs %d%%)",
            strtoupper($c['w']), (int)round($c['gap']),
            (int)$c['pHt'], (int)$c['pG']);
    }

    // Muestras bajas
    $lowHt = array_filter($tablaHt, fn($r)=> ($r['matches']??0)>0 && $r['matches']<8);
    $lowG  = array_filter($tablaG,  fn($r)=> ($r['matches']??0)>0 && $r['matches']<12);
    if (!empty($lowHt)) $riesgos[] = "Muestras HT <8 en alguna(s) ventana(s) (posible sobreajuste).";
    if (!empty($lowG))  $riesgos[] = "Muestras Global <12 en alguna(s) ventana(s).";
   // ===== SP: evaluar riesgo según sentido y tramos =====
#$spAux = $payload['sp_auxiliar'] ?? ($payload['metadatos']['sp_ge3_filtered'] ?? null);
// ===== SP: evaluar riesgo según sentido y tramos (robusto) =====
$spAux = data_get($payload, 'sp_auxiliar');
if ($spAux === null) {
    // fallbacks comunes
    $spAux = data_get($payload, 'metadatos.sp_dynamic_used');
    if ($spAux === null) $spAux = data_get($payload, 'metadatos.sp_ge3_filtered');
}
if ($spAux !== null) {
    $spAux = (float)$spAux; // ORIENTADO AL SENTIDO:
                            // - OVER: prob de ALCANZAR
                            // - UNDER: prob de NO ALCANZAR

    // Prob de ALCANZAR el spread (criterio común de riesgo)
    $probAlcanzar = ($sentido === 'UNDER') ? (1.0 - $spAux) : $spAux;
    // Prob "a favor" del sentido actual (lo que quieres que ocurra)
    $probAFavor   = ($sentido === 'UNDER') ? $spAux : $spAux;

    $alcanzarPct = $this->asPct($probAlcanzar);
    $aFavorPct   = $this->asPct($probAFavor);
    $sideLabel   = $sentido;

    // Tramos simétricos por sentido:
    // UNDER: alto riesgo si es fácil ALCANZAR (probAlcanzar grande)
    // OVER:  alto riesgo si es difícil ALCANZAR (probAlcanzar chico)
    if ($sentido === 'UNDER') {
        if ($probAlcanzar < 0.25) {
            $razones[] = "SP(alcanzar spread en 2T) bajo: {$alcanzarPct}% (a favor de UNDER: {$aFavorPct}%).";
        } elseif ($probAlcanzar <= 0.40) {
            $riesgos[] = "SP(alcanzar spread en 2T) moderado: {$alcanzarPct}% (a favor de UNDER: {$aFavorPct}%).";
        } else {
            $riesgos[] = "SP(alcanzar spread en 2T) alto: {$alcanzarPct}% (contra UNDER).";
        }
    } else { // OVER
        if ($probAlcanzar > 0.75) {
            $razones[] = "SP(alcanzar spread en 2T) favorable: {$alcanzarPct}% (a favor de OVER).";
        } elseif ($probAlcanzar >= 0.60) {
            $riesgos[] = "SP(alcanzar spread en 2T) moderado: {$alcanzarPct}% (a favor de OVER).";
        } else {
            $riesgos[] = "SP(alcanzar spread en 2T) bajo: {$alcanzarPct}% (contra OVER).";
        }
    }
}
// ===== fin bloque SP =====

    // ========================================

    if (count($razones) < 3) $razones[] = "Consistencia moderada entre ventanas y baseline.";
    if (count($riesgos) < 2) $riesgos[] = "Señal sensible a outliers en ventana corta (Hoy/3D).";

    return ['racional'=>$razones, 'riesgos'=>$riesgos];
}

    public function evaluar(Request $r, AdvisorService $svc)
    {
        $ctx      = $r->input('contexto', []);
        $sentido  = strtoupper((string) data_get($ctx, 'sentido', 'UNDER')); // "OVER" | "UNDER"

        $tablaHt = $r->input('tabla_ht', []);
        $tablaG  = $r->input('tabla_global', []);

        // Prob objetivo 0..1 según sentido (si no viene, se calcula de tablas)
        $p_obj_ht     = $r->input('p_objetivo_ht');
        $p_obj_global = $r->input('p_objetivo_global');

        if ($p_obj_ht === null || $p_obj_global === null) {
            $field = ($sentido === 'OVER') ? 'market_over' : 'market_under';
            $p_ht_calc = $this->weightedAvg($tablaHt, $field);
            $p_gl_calc = $this->weightedAvg($tablaG,  $field);
            $p_obj_ht     = $p_obj_ht     ?? $p_ht_calc;
            $p_obj_global = $p_obj_global ?? $p_gl_calc;
        }

        // Convertimos a UNDER para el baseline interno (compat)
        if ($sentido === 'UNDER') {
            $p_under_ht     = ($p_obj_ht     !== null) ? (float)$p_obj_ht : null;
            $p_under_global = ($p_obj_global !== null) ? (float)$p_obj_global : null;
        } else {
            $p_under_ht     = ($p_obj_ht     !== null) ? (1.0 - (float)$p_obj_ht) : null;
            $p_under_global = ($p_obj_global !== null) ? (1.0 - (float)$p_obj_global) : null;
        }

        $sp_aux = $r->input('sp_auxiliar'); // ya en 0..1 acorde al sentido (desde el front)

        $payload = [
            "contexto_partido" => $ctx,
            "tabla_ht_resumen" => $tablaHt,
            "tabla_global_resumen" => $tablaG,
            "ultimos_partidos" => $r->input('ultimos', []),
            "features" => [
                "p_under_ht"     => ($p_under_ht     !== null ? (float)$p_under_ht     : null),
                "p_under_global" => ($p_under_global !== null ? (float)$p_under_global : null),
                "n_ht"           => (int)($r->input('n_ht') ?? $this->weightedCount($tablaHt)),
                "n_global"       => (int)($r->input('n_global') ?? $this->weightedCount($tablaG)),
            ],
            "metadatos" => [
                "sp_ge3_filtered" => $r->input('metadatos.sp_dynamic_used', null), // opcional/legacy
                "sp_ref_label"    => $r->input('metadatos.sp_ref_label', null),
                "version"         => data_get($ctx, 'version_juego')
            ],
            "sp_auxiliar"        => ($sp_aux !== null ? (float)$sp_aux : null), // OVER: prob alcanzar; UNDER: prob mantenerse
            "p_objetivo_ht"      => $p_obj_ht,      // 0..1 en sentido objetivo
            "p_objetivo_global"  => $p_obj_global,  // 0..1
        ];

        // Baseline (en UNDER) — ahora el servicio convierte SP si sentido=OVER
        $baselineUnder  = $svc->baselineScore($payload);

        // Transformación del baseline al sentido objetivo:
        $baselineTarget = $baselineUnder;
        if (isset($baselineUnder['prob'])) {
            $baselineTarget['prob'] = ($sentido === 'OVER')
                ? (1.0 - (float)$baselineUnder['prob'])
                : (float)$baselineUnder['prob'];
        }

        // LLM con ambos baselines (usa baseline_target)
        $gpt = $svc->explainWithGPT($payload, $baselineUnder, $baselineTarget);

        // Normalización robusta a 0..1
        $prob = isset($gpt['probabilidad']) ? (float)$gpt['probabilidad'] : (float)($baselineTarget['prob'] ?? 0.0);
        $conf = isset($gpt['confianza'])    ? (float)$gpt['confianza']    : (float)($baselineTarget['confianza'] ?? 0.0);

        if ($prob > 1.0) $prob = ($prob <= 100.0) ? ($prob / 100.0) : 1.0;
        if ($prob < 0.0) $prob = 0.0;
        if ($conf > 1.0) $conf = ($conf <= 100.0) ? ($conf / 100.0) : 1.0;
        if ($conf < 0.0) $conf = 0.0;

        $decision = $gpt['decision'] ?? (
            ($prob >= 0.65 && $conf >= 0.55) ? 'TAKE' :
            (($prob <= 0.45) ? 'SKIP' : 'NO_SIGNAL')
        );

        $racional = $gpt['racional'] ?? [];
        $riesgos  = $gpt['riesgos']  ?? [];
        if (empty($racional) || empty($riesgos)) {
            $fb = $this->fallbackExplain(
                [
                    'tabla_ht_resumen'     => $tablaHt,
                    'tabla_global_resumen' => $tablaG,
                    'metadatos'            => ['sp_ge3_filtered' => ($sp_aux ?? null)]
                ],
                $baselineTarget, // ya en sentido objetivo
                $sentido
            );
            if (empty($racional)) $racional = $fb['racional'];
            if (empty($riesgos))  $riesgos  = $fb['riesgos'];
        }

        // ===== DEBUG extra para cuadrar números =====
        $debug = [
            'sentido' => $sentido,
            'agg' => [
                'over_ht'     => $this->weightedAvg($tablaHt, 'market_over'),
                'over_global' => $this->weightedAvg($tablaG,  'market_over'),
                'under_ht'    => $this->weightedAvg($tablaHt, 'market_under'),
                'under_global'=> $this->weightedAvg($tablaG,  'market_under'),
            ],
            'p_objetivo_ht'      => $p_obj_ht,
            'p_objetivo_global'  => $p_obj_global,
            'p_under_ht'         => $p_under_ht,
            'p_under_global'     => $p_under_global,
            'sp_auxiliar'        => $sp_aux, // orientado al sentido
            'baseline_under'     => $baselineUnder,
            'baseline_target'    => $baselineTarget,
        ];

        return response()->json([
            'decision'     => $decision,
            'probabilidad' => round($prob, 3),
            'confianza'    => round($conf, 3),
            'explicacion'  => [
                'racional' => $racional,
                'riesgos'  => $riesgos,
            ],
            'parametros'   => array_merge($baselineTarget, [
                'sentido'            => $sentido,
                'p_objetivo_ht'      => $p_obj_ht,
                'p_objetivo_global'  => $p_obj_global,
                'p_under_ht_usado'   => $payload['features']['p_under_ht'],
                'p_under_glob_usado' => $payload['features']['p_under_global'],
                'sp_auxiliar'        => $sp_aux,
                'sp_ref_label'       => $payload['metadatos']['sp_ref_label'] ?? null,
            ]),
            'debug' => $debug,
        ]);
    }
}
