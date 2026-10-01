<?php
// app/Services/AdvisorService.php

namespace App\Services;

class AdvisorService
{
    protected $base;
    protected $model;
    protected $key;

    public function __construct()
    {
        $this->base  = rtrim(env('OPENAI_BASE', 'https://api.openai.com'), '/').'/v1/';
        $this->model = env('OPENAI_MODEL', 'gpt-5-mini');
        $this->key   = env('OPENAI_API_KEY');
    }

    /* Baseline en UNDER (mezcla HT/Global + SP convertido si sentido=OVER) */
    public function baselineScore(array $f): array
    {
        // Probabilidades en UNDER 0..1
        $pHt = (float)($f['features']['p_under_ht']     ?? 0.0);
        $pG  = (float)($f['features']['p_under_global'] ?? 0.0);
        $nHt = max(1, (int)($f['features']['n_ht']      ?? 1));
        $nG  = max(1, (int)($f['features']['n_global']  ?? 1));

        $wHt = min(1.0, log(1+$nHt)/log(1+200));
        $pUG = $wHt*$pHt + (1-$wHt)*$pG;         // UNDER 0..1
        $nEf = $wHt*$nHt + (1-$wHt)*$nG;
        $confBase = max(0.0, min(1.0, $nEf/200));

        // SP dinámico 0..1 desde el front (orientado al SENTIDO)
        $sense = strtoupper((string) data_get($f, 'contexto_partido.sentido', 'UNDER')); // OVER|UNDER
        $sp = null;
        if (isset($f['sp_auxiliar'])) {
            $sp = (float)$f['sp_auxiliar']; // OVER: prob alcanzar; UNDER: prob mantenerse
        } elseif (isset($f['metadatos']['sp_ge3_filtered'])) {
            $sp = (float)$f['metadatos']['sp_ge3_filtered']; // legacy
        }

        // Convertimos el SP al “espacio UNDER” para mezclar con pUG
        $sp_for_under = null;
        if ($sp !== null) {
            $sp_for_under = ($sense === 'OVER') ? (1.0 - $sp) : $sp;
        }

        $p = ($sp_for_under !== null) ? (0.65*$pUG + 0.35*$sp_for_under) : $pUG;

        return [
            'prob'         => $p,          // UNDER 0..1
            'confianza'    => $confBase,   // 0..1
            'n_eff'        => $nEf,
            'w_ht'         => $wHt,
            'p_mix_ug'     => $pUG,
            'sp_used_raw'  => $sp,             // como llegó (en sentido)
            'sp_for_under' => $sp_for_under,   // convertido para mezcla
            'sense'        => $sense,
        ];
    }

    public function explainWithGPT(array $payload, array $baselineUnder, array $baselineTarget): array
    {
        $schema = [
            "name" => "advisor_output",
            "schema" => [
                "type" => "object",
                "properties" => [
                    "decision"     => ["type"=>"string","enum"=>["TAKE","SKIP","NO_SIGNAL"]],
                    "probabilidad" => ["type"=>"number"],
                    "confianza"    => ["type"=>"number"],
                    "racional"     => ["type"=>"array","minItems"=>3,"items"=>["type"=>"string"]],
                    "riesgos"      => ["type"=>"array","minItems"=>2,"items"=>["type"=>"string"]],
                    "parametros"   => ["type"=>"object"]
                ],
                "required" => ["decision","probabilidad","confianza","racional","riesgos"]
            ]
        ];

        $system_1 =
            "Devuelve SOLO JSON con el esquema indicado.\n".
            "- 'probabilidad' y 'confianza' en ESCALA 0..1 (no en %).\n".
            "- Respeta el 'sentido' (OVER o UNDER) del contexto: la prob devuelta es para ese sentido.\n".
            "- Usa TODAS las ventanas (tabla_ht y tabla_global) con sus tamaños n.\n".
            "- 'sp_auxiliar' (0..1):\n".
            "  * OVER: prob. de anotar los goles faltantes en 2T.\n".
            "  * UNDER: prob. de NO anotar los goles que romperían el under.\n".
            "- Usa 'baseline_target' (ya en el sentido correcto) como referencia.\n".
            "- 'racional': ≥3 bullets con ventanas y cifras; 'riesgos': ≥2 bullets.\n".
            "- No inventes datos; usa porcentajes/n reales.
            - huella_de_calculo: objeto con
    * ponderaciones_usadas: cada ventana con cálculo (n / sum(n) → peso)
    * formula_probabilidad
    * formula_confianza
    * baseline_target
    * sentido";

        $system_2 = "Actúa como un analista estadístico especializado en eSoccer FIFA 8 minutos.
Analiza los datos recibidos y devuelve ÚNICAMENTE un JSON válido.
Reglas de cálculo:
1. Usa TODAS las ventanas de `tabla_ht` y `tabla_global`, ponderadas por su tamaño de muestra `n`.
2. Mantén el sentido recibido en el contexto (OVER o UNDER):
   - OVER: 'probabilidad' es la chance de alcanzar o superar el market.
   - UNDER: 'probabilidad' es la chance de mantenerse por debajo del market.
3. `sp_auxiliar`:
   - OVER: probabilidad de anotar los goles faltantes en el 2T.
   - UNDER: probabilidad de NO anotar los goles que romperían el under.
4. Usa `baseline_target` (ya en el sentido correcto) como referencia estadística principal.
5. Todas las probabilidades en escala 0..1 (no en %). No inventes datos: usa solo los valores reales disponibles.
6. En `racional` y `riesgos`, cada bullet debe incluir la ventana, tamaño `n` y valor exacto (% o probabilidad) que respalde la conclusión.

No incluyas texto fuera del JSON. Responde solo el JSON.";

$system = $system_1;

        $user = [
            "contexto_partido" => $payload["contexto_partido"],
            "tabla_ht"         => $payload["tabla_ht_resumen"],
            "tabla_global"     => $payload["tabla_global_resumen"],
            "ultimos_partidos" => $payload["ultimos_partidos"],
            "baseline_under"   => $baselineUnder,
            "baseline_target"  => $baselineTarget,
            "metadatos"        => array_merge(
                ($payload["metadatos"] ?? []),
                [
                    "sp_auxiliar"       => $payload["sp_auxiliar"]       ?? null,
                    "sp_ref_label"      => $payload["metadatos"]["sp_ref_label"] ?? null,
                    "p_objetivo_ht"     => $payload["p_objetivo_ht"]     ?? null,
                    "p_objetivo_global" => $payload["p_objetivo_global"] ?? null
                ]
            ),
        ];

        $body = [
            'model' => $this->model,
            'input' => [
                ['role'=>'system','content'=>$system],
                ['role'=>'user','content'=>json_encode($user, JSON_UNESCAPED_UNICODE)]
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => $schema
            ]
        ];

        $resp = $this->postJson('responses', $body);

        $text = $resp['output'][0]['content'][0]['text'] ?? '{}';
        $json = json_decode($text, true);
        if (is_array($json)) {
            if (isset($json['probabilidad'])) {
                $p = (float)$json['probabilidad'];
                if ($p > 1.0) $p = ($p <= 100.0) ? ($p/100.0) : 1.0;
                if ($p < 0.0) $p = 0.0;
                $json['probabilidad'] = $p;
            }
            if (isset($json['confianza'])) {
                $c = (float)$json['confianza'];
                if ($c > 1.0) $c = ($c <= 100.0) ? ($c/100.0) : 1.0;
                if ($c < 0.0) $c = 0.0;
                $json['confianza'] = $c;
            }
        }

        return is_array($json) ? $json : [];
    }

    protected function postJson(string $path, array $payload): array
    {
        $ch = curl_init($this->base.$path);
        $headers = [
            'Authorization: Bearer '.$this->key,
            'Content-Type: application/json'
        ];
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 25,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_POSTFIELDS     => json_encode($payload)
        ]);
        $out = curl_exec($ch);
        if ($out === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return ['error' => $err];
        }
        curl_close($ch);
        $data = json_decode($out, true);
        return is_array($data) ? $data : ['raw'=>$out];
    }
}
