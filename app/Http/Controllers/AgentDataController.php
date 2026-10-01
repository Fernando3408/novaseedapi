<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentDataController extends Controller
{
    private $agentMarkets = [
        'chips' => ['Chips'],
        'french' => ['French Fries', 'FF', 'FF/CH'],
        'table' => ['Table', 'Double Propouse', 'Yield'],
        'testigos' => [],
        'table-color-salad' => ['Table Color', 'Salad Type', 'Bi-Color'],
        'baby' => ['Baby'],
        'pigmentos' => ['Pigments', 'Fitonutrientes', 'Bi-Color', 'Table Color'],
        'mantencion' => [],
        'jardin-chilote' => [],
        'fitopatologo' => ['Phytophtora'],
        'genetista-estadistico' => [],
    ];

    public function markets()
    {
        return response()->json([
            'data' => DB::table('markets')->select('id', 'name')->orderBy('id')->get(),
        ]);
    }

    public function standards(Request $request)
    {
        $marketIds = $this->marketIds($request);

        $stdmarkets = DB::table('stdmarkets as s')
            ->leftJoin('markets as m', 'm.id', '=', 's.market_id')
            ->when($marketIds, function ($q) use ($marketIds) {
                $q->whereIn('s.market_id', $marketIds);
            })
            ->select('s.id', 's.name', 's.value', 's.varities_string', 'm.id as market_id', 'm.name as market')
            ->orderBy('m.name')->orderBy('s.name')
            ->get();

        $relative = DB::table('relative_yields as r')
            ->leftJoin('markets as m', 'm.id', '=', 'r.market_id')
            ->when($marketIds, function ($q) use ($marketIds) {
                $q->whereIn('r.market_id', $marketIds);
            })
            ->select('r.id', 'r.name', 'r.std', 'm.id as market_id', 'm.name as market')
            ->orderBy('m.name')->orderBy('r.name')
            ->get();

        return response()->json(['stdmarkets' => $stdmarkets, 'relative_yield_standards' => $relative]);
    }

    public function varieties(Request $request)
    {
        $marketIds = $this->marketIds($request);
        $limit = min((int) $request->get('limit', 100), 500);

        $rows = DB::table('varities as v')
            ->leftJoin('markets as m', 'm.id', '=', 'v.market_id')
            ->leftJoin('varities as mother', 'mother.id', '=', 'v.mother_id')
            ->leftJoin('varities as father', 'father.id', '=', 'v.father_id')
            ->when($marketIds, function ($q) use ($marketIds) {
                $q->whereIn('v.market_id', $marketIds);
            })
            ->when($request->get('q'), function ($q) use ($request) {
                $term = '%' . $request->get('q') . '%';
                $q->where(function ($w) use ($term) {
                    $w->where('v.name', 'like', $term)->orWhere('v.code', 'like', $term);
                });
            })
            ->when($request->get('year'), function ($q) use ($request) {
                $q->where('v.year', $request->get('year'));
            })
            ->whereNull('v.deleted_at')
            ->select(
                'v.id', 'v.code', 'v.name', 'v.year', 'v.crossing', 'v.typecrossing',
                'v.berries', 'v.potatoes', 'v.glasses', 'v.observation',
                'm.id as market_id', 'm.name as market',
                'mother.id as mother_id', DB::raw('COALESCE(mother.code, mother.name) as mother'),
                'father.id as father_id', DB::raw('COALESCE(father.code, father.name) as father')
            )
            ->orderBy('v.year', 'desc')->orderBy('v.code')->limit($limit)->get();

        return response()->json(['data' => $rows]);
    }

    public function avgCharacteristics(Request $request)
    {
        $marketIds = $this->marketIds($request);
        $limit = min((int) $request->get('limit', 300), 1000);

        $rows = DB::table('characteristics as c')
            ->join('varities as v', 'v.id', '=', 'c.varity_id')
            ->leftJoin('markets as m', 'm.id', '=', 'v.market_id')
            ->join('features as f', 'f.id', '=', 'c.feature_id')
            ->leftJoin('localities as l', 'l.id', '=', 'c.locality_id')
            ->when($marketIds, function ($q) use ($marketIds) {
                $q->whereIn('v.market_id', $marketIds);
            })
            ->when($request->get('variety_id'), function ($q) use ($request) {
                $q->where('v.id', $request->get('variety_id'));
            })
            ->when($request->get('feature_id'), function ($q) use ($request) {
                $q->where('f.id', $request->get('feature_id'));
            })
            ->when($request->get('locality_id'), function ($q) use ($request) {
                $q->where('c.locality_id', $request->get('locality_id'));
            })
            ->whereIn('c.year', [0, 1, 2, 3, 4])
            ->where('c.feature_id', '>', 0)
            ->whereNull('v.deleted_at')
            ->groupBy('v.id', 'v.code', 'v.name', 'm.name', 'f.id', 'f.name', 'l.id', 'l.name')
            ->select(
                'v.id as variety_id', 'v.code', 'v.name as variety', 'm.name as market',
                'f.id as feature_id', 'f.name as feature',
                'l.id as locality_id', 'l.name as locality',
                DB::raw('COUNT(*) as n'),
                DB::raw('AVG(CAST(NULLIF(c.value, "") AS DECIMAL(14,4))) as avg_value'),
                DB::raw('VAR_POP(CAST(NULLIF(c.value, "") AS DECIMAL(14,4))) as variance_value'),
                DB::raw('STDDEV_POP(CAST(NULLIF(c.value, "") AS DECIMAL(14,4))) as stddev_value'),
                DB::raw('MAX(CASE WHEN c.year = 0 THEN c.value END) as year_1'),
                DB::raw('MAX(CASE WHEN c.year = 1 THEN c.value END) as year_2'),
                DB::raw('MAX(CASE WHEN c.year = 2 THEN c.value END) as year_3'),
                DB::raw('MAX(CASE WHEN c.year = 3 THEN c.value END) as year_4'),
                DB::raw('MAX(CASE WHEN c.year = 4 THEN c.value END) as year_5'),
                DB::raw('GROUP_CONCAT(c.value ORDER BY c.id SEPARATOR ", ") as raw_values')
            )
            ->orderBy('m.name')->orderBy('v.code')->orderBy('f.name')->limit($limit)->get();

        return response()->json(['data' => $rows]);
    }

    public function resultsSummary(Request $request)
    {
        $marketIds = $this->marketIds($request);
        $limit = min((int) $request->get('limit', 100), 500);

        $varieties = DB::table('varities as v')
            ->leftJoin('markets as m', 'm.id', '=', 'v.market_id')
            ->leftJoin('varities as mother', 'mother.id', '=', 'v.mother_id')
            ->leftJoin('varities as father', 'father.id', '=', 'v.father_id')
            ->when($marketIds, function ($q) use ($marketIds) {
                $q->whereIn('v.market_id', $marketIds);
            })
            ->when($request->get('q'), function ($q) use ($request) {
                $term = '%' . $request->get('q') . '%';
                $q->where(function ($w) use ($term) {
                    $w->where('v.name', 'like', $term)->orWhere('v.code', 'like', $term);
                });
            })
            ->whereNull('v.deleted_at')
            ->select(
                'v.id', 'v.code', 'v.name', 'v.year', 'v.berries', 'v.potatoes', 'v.glasses',
                'm.name as market',
                DB::raw('COALESCE(mother.code, mother.name) as mother'),
                DB::raw('COALESCE(father.code, father.name) as father')
            )
            ->orderBy('v.year', 'desc')->orderBy('v.code')->limit($limit)->get();

        $ids = $varieties->pluck('id')->toArray();
        $features = DB::table('features')->where('status', 1)->orderBy('id')->get();

        $chars = DB::table('characteristics')
            ->whereIn('varity_id', $ids ?: [0])
            ->whereIn('year', [0, 1, 2, 3, 4])
            ->where('value', '!=', '')
            ->get()
            ->groupBy(function ($row) {
                return $row->varity_id . ':' . $row->feature_id;
            });

        $data = [];
        foreach ($varieties as $variety) {
            $item = [
                'variety' => $variety,
                'features' => [],
            ];

            foreach ($features as $feature) {
                $rows = $chars->get($variety->id . ':' . $feature->id, collect());
                if ($rows->count() === 0) {
                    continue;
                }
                $values = [];
                $byYear = ['year_1' => null, 'year_2' => null, 'year_3' => null, 'year_4' => null, 'year_5' => null];
                foreach ($rows as $row) {
                    $key = 'year_' . ((int) $row->year + 1);
                    $byYear[$key] = $row->value;
                    if (is_numeric($row->value)) {
                        $values[] = (float) $row->value;
                    }
                }
                $avg = count($values) ? round(array_sum($values) / count($values), 2) : $rows->first()->value;
                $variance = count($values) ? $this->variance($values) : null;

                $item['features'][] = array_merge([
                    'feature_id' => $feature->id,
                    'feature' => $feature->name,
                    'avg' => $avg,
                    'variance' => $variance,
                    'stddev' => is_null($variance) ? null : round(sqrt($variance), 2),
                    'n' => $rows->count(),
                ], $byYear);
            }
            $data[] = $item;
        }

        return response()->json(['data' => $data]);
    }

    public function relativeYields(Request $request)
    {
        $marketIds = $this->marketIds($request);

        $rows = DB::table('relative_yields as ry')
            ->leftJoin('markets as m', 'm.id', '=', 'ry.market_id')
            ->leftJoin('relative_yields_varities as rv', 'rv.relative_yield_id', '=', 'ry.id')
            ->leftJoin('varities as v', 'v.id', '=', 'rv.varity_id')
            ->when($marketIds, function ($q) use ($marketIds) {
                $q->whereIn('ry.market_id', $marketIds);
            })
            ->when($request->get('variety_id'), function ($q) use ($request) {
                $q->where('v.id', $request->get('variety_id'));
            })
            ->select(
                'ry.id', 'ry.name', 'ry.std', 'm.name as market',
                'v.id as variety_id', 'v.code', 'v.name as variety'
            )
            ->orderBy('m.name')->orderBy('ry.name')->orderBy('v.code')
            ->limit(500)->get();

        return response()->json(['data' => $rows]);
    }

    public function greenhouse(Request $request)
    {
        $marketIds = $this->marketIds($request);
        $rows = DB::table('varities as v')
            ->join('greenhouse as g', 'g.id', '=', 'v.greenhouse_id')
            ->leftJoin('markets as m', 'm.id', '=', 'v.market_id')
            ->leftJoin('varities as mother', 'mother.id', '=', 'v.mother_id')
            ->leftJoin('varities as father', 'father.id', '=', 'v.father_id')
            ->when($marketIds, function ($q) use ($marketIds) {
                $q->whereIn('v.market_id', $marketIds);
            })
            ->where('v.greenhouse_id', '>', 0)
            ->whereNull('v.deleted_at')
            ->select(
                'g.id as greenhouse_id', 'g.name as greenhouse',
                'v.id as variety_id', 'v.code', 'v.name as variety', 'v.year',
                'v.berries', 'v.berries_objective', 'v.glasses', 'v.potatoes',
                'm.name as market',
                DB::raw('COALESCE(mother.code, mother.name) as mother'),
                DB::raw('COALESCE(father.code, father.name) as father')
            )
            ->orderBy('g.name')->orderBy('v.year', 'desc')->limit(500)->get();

        return response()->json(['data' => $rows]);
    }

    public function context($agent, Request $request)
    {
        $request->merge(['agent' => $agent, 'limit' => $request->get('limit', 80)]);

        return response()->json([
            'agent' => $agent,
            'markets' => $this->agentMarkets[$agent] ?? [],
            'endpoints' => [
                'markets' => url('/api/agents/markets'),
                'varieties' => url('/api/agents/varieties?agent=' . $agent),
                'avg_characteristics' => url('/api/agents/avg-characteristics?agent=' . $agent . '&years=5'),
                'results_summary' => url('/api/agents/results-summary?agent=' . $agent),
                'standards' => url('/api/agents/standards?agent=' . $agent),
                'relative_yields' => url('/api/agents/relative-yields?agent=' . $agent),
                'greenhouse' => url('/api/agents/greenhouse?agent=' . $agent),
            ],
            'varieties' => json_decode($this->varieties($request)->getContent(), true)['data'],
            'standards' => json_decode($this->standards($request)->getContent(), true),
        ]);
    }

    private function marketIds(Request $request)
    {
        $names = [];
        if ($request->get('market')) {
            $names = array_map('trim', explode(',', $request->get('market')));
        } elseif ($request->get('agent') && array_key_exists($request->get('agent'), $this->agentMarkets)) {
            $names = $this->agentMarkets[$request->get('agent')];
        }

        if (!$names) {
            return [];
        }

        return DB::table('markets')->whereIn('name', $names)->pluck('id')->toArray();
    }

    private function variance($values)
    {
        $n = count($values);
        if ($n === 0) {
            return null;
        }
        $mean = array_sum($values) / $n;
        $sum = 0;
        foreach ($values as $value) {
            $sum += pow($value - $mean, 2);
        }
        return round($sum / $n, 2);
    }
}
