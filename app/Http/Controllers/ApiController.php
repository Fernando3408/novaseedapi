<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use App\Models\Varity;
use App\Models\Blocksvarity;
use App\Models\Typefeature;
use App\Models\Characteristic;
use App\Models\Showfarm;
use App\Models\Feature;
use App\Models\Value;
use App\Support\FeatureGuideBuilder;
use Illuminate\Database\Eloquent\Builder;
use App\Avg_characteristic;
use App\Fries_avg_characteristics;
use App\fries_characteristics;


class ApiController extends Controller
{
    /**
     * Returns annual and monthly frying data for one variety and feature.
     */
    public function fryingDetail($variety, $feature)
    {
        $varietyModel = Varity::withTrashed()->find($variety);
        $featureModel = Feature::find($feature);

        if (!$varietyModel || !$featureModel) {
            return response()->json([
                'status' => false,
                'message' => 'La variedad o la característica no existe.',
            ], 404);
        }

        $factors = [
            'marzo' => 0.071,
            'abril' => 0.077,
            'mayo' => 0.084,
            'junio' => 0.090,
            'julio' => 0.097,
            'agosto' => 0.103,
            'septiembre' => 0.110,
            'octubre' => 0.116,
            'noviembre' => 0.123,
        ];

        $months = [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
            'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre',
        ];

        $annual = Characteristic::where('varity_id', $variety)
            ->where('feature_id', $feature)
            ->orderBy('year')
            ->get()
            ->map(function ($row) {
                return [
                    'year' => $row->year,
                    'value' => $row->value,
                    'block_id' => $row->block_id,
                    'locality_id' => $row->locality_id,
                ];
            })->values();

        $monthly = fries_characteristics::where('varity_id', $variety)
            ->where(function ($query) use ($feature) {
                $query->where('feature', $feature)
                    ->orWhere('characteristic_id', $feature);
            })
            ->orderBy('year')
            ->get()
            ->map(function ($row) use ($months, $factors) {
                $monthlyValues = [];
                foreach ($months as $month) {
                    $monthlyValues[$month] = $row->{$month};
                }

                $weighted = 0;
                foreach ($factors as $month => $factor) {
                    $weighted += (float) ($row->{$month} ?: 0) * $factor;
                }

                return [
                    'year' => $row->year,
                    'values' => $monthlyValues,
                    'weighted_total' => round($weighted, 3),
                    'block_id' => $row->block_id,
                    'locality_id' => $row->locality_id,
                    'source_id' => $row->id,
                ];
            })->values();

        $precalculated = Fries_avg_characteristics::where('varity_id', $variety)
            ->where('feature_id', $feature)
            ->first();

        $annualByYear = $annual->keyBy(function ($row) {
            return (string) $row['year'];
        });

        $yearDetails = $monthly->map(function ($row) use ($annualByYear) {
            $annualRow = $annualByYear->get((string) $row['year']);

            return [
                'year' => $row['year'],
                'annual_value' => $annualRow ? $annualRow['value'] : null,
                'months' => $row['values'],
                'weighted_total' => $row['weighted_total'],
                'block_id' => $annualRow ? $annualRow['block_id'] : $row['block_id'],
                'locality_id' => $annualRow ? $annualRow['locality_id'] : $row['locality_id'],
            ];
        })->values();

        $annualNumericValues = $annual->pluck('value')
            ->filter(function ($value) {
                return is_numeric($value);
            })
            ->map(function ($value) {
                return (float) $value;
            });

        return response()->json([
            'status' => true,
            'variety' => [
                'id' => (int) $varietyModel->id,
                'name' => $varietyModel->name,
                'code' => $varietyModel->code,
            ],
            'feature' => [
                'id' => (int) $featureModel->id,
                'name' => $featureModel->name,
                'frying_test' => $featureModel->frying_test,
            ],
            'factors' => $factors,
            // Results uses fries_avg_characteristics.PROMEDIO for this value.
            'value_avg' => $precalculated && $precalculated->PROMEDIO !== null
                ? (float) $precalculated->PROMEDIO
                : ($annualNumericValues->count() > 0 ? round($annualNumericValues->avg(), 3) : null),
            'annual_average_raw' => $annualNumericValues->count() > 0
                ? round($annualNumericValues->avg(), 3)
                : null,
            'value_ponderate' => $precalculated && $precalculated->ponderado !== null
                ? (float) $precalculated->ponderado
                : null,
            'annual_values' => $annual,
            'monthly_details' => $monthly,
            'year_details' => $yearDetails,
            'precalculated' => $precalculated ? [
                'average' => $precalculated->PROMEDIO,
                'weighted' => $precalculated->ponderado,
            ] : null,
        ]);
    }

    protected function normalizeSelectedValues($value)
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_unique(array_map(function ($item) {
            return is_string($item) ? trim($item) : $item;
        }, array_values($value))));
    }

    protected function areAllSelectedValuesNumeric(array $values)
    {
        if (empty($values)) {
            return false;
        }

        foreach ($values as $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }

        return true;
    }

    protected function sanitizeVarityLabel($value)
    {
        if ($value === null) {
            return null;
        }

        return trim(strip_tags((string) $value));
    }

    public function export(){

    	$typefeature = Typefeature::all();
    	$feature = Feature::all();
    	$block = Block::where('offline',1)->get();
    	$blocksvarity = [];
    	$varities = [];
    	$characteristics = [];

    	foreach ($block as $key => $value) {
    		$b = Blocksvarity::where('block_id', $value->id)->get();

    		foreach ($b as $k => $v) {
    			$t = [
    				"id" => (int)$v->id,
    				'varity_id' => (int)$v->varity_id,
    				'block_id' => (int)$v->block_id,
    				'position' => (string)$v->position,
    				'type' => (string)$v->type,
    				'state' => 1,
    				'observation' => (string)$v->observation,
    				'last_position' => (int)$v->last_position,
    				'review' => (int)$v->review,
    				"created_at" => (string)$v->created_at,
    				"updated_at" => (string)$v->updated_at,
    			];

    			array_push($blocksvarity, $t);

    		}

    		$model = Blocksvarity::where('block_id', $value->id)->groupBy('varity_id')->distinct()->get();

    		foreach ($model as $k => $valores_) {
    		
    			$varity = $valores_->varity()->first();

    			if(!empty($varity)){
    				$valores = [
	    				"id" => (int)$varity->id,
	    				"mother_id" => (int)$varity->mother_id,
	    				"father_id" => (int)$varity->father_id,
	    				"code" => !empty($varity->code) ? (string)$varity->code : "-",
	    				"year" => (int)$varity->year,
	    				"name" => !empty($varity->name) ?(string)$varity->name: "-",
	    				"berries" => (int)$varity->berries,
	    				"crossing" => (int)$varity->crossing,
	    				"typecrossing" => (int)$varity->typecrossing,
	    				"observation" => empty($varity->observation) ?(string)$varity->observation: "-",
	    				"parent_id" => (int)$varity->parent_id,
	    				"user_id" => (int)$varity->user_id,
	    				"market_id" => (int)$varity->market_id,
	    				"slug" => empty($varity->slug) ?(string)$varity->slug : "-",
	    				"marketold_id" => !empty($varity->marketold_id) ? (int)$varity->marketold_id : (int)$varity->market_id,
	    				"mapout_id" => (int)$varity->mapout_id,
	    				"berries_objective" => (int)$varity->berries_objective,
	    				"status" => (int)$varity->status,
	    				"polem_save" => (int)$varity->polem_save,
	    				"glasses" => (int)$varity->glasses,
	    				"greenhouse_id" => (int)$varity->greenhouse_id,
	    				"created_at" => (string)$varity->created_at,
	    				"updated_at" => (string)$varity->updated_at
	    			];

	    			if(!$this->in_array_field("id",$varity->id, $varities)){
	    				array_push($varities, $valores);

	    				$char = $varity->characteristics()->get();

		    			if(!empty($char)){

		    				foreach ($char as $k_ => $v_) {
		    					$c = [
				    				"id" => (int)@$v_->id,
				    				"value" => (string)$v_->value,
				    				"feature_id" => (int)$v_->feature_id,
				    				"varity_id" => (int)$v_->varity_id,
				    				"year" => (int)$v_->year,
				    				"zonaltrial_id" => (int)$v_->zonaltrial_id,
				    				"block_id" => (int)$v_->block_id,
				    				"repeats_values" => (string)$v_->repeats_values,
				    				"created_at" => (string)$v_->created_at,
				    				"updated_at" => (string)$v_->updated_at,

				    			];

				    			array_push($characteristics, $c);
		    				}
		    			}
	    			}
    			}
    		}

    	}

    	$blocks = [];
    	$confBlock = [];

    	foreach ($block as $key => $value) {
    		$temp = [
    			"id" => $value->id,
    			"description" => $value->description,
    			"lines" => (int)$value->lines,
    			"limit" => (int)$value->limit,
    			"year" => (int)$value->year,
    			"latitud" => $value->latitud,
    			"longitud" => $value->longitud,
    			"offline" => (int)$value->ofline,
    			"created_at" => (string)$value->created_at,
    			"updated_at" => (string)$value->updated_at,
    		];	

    		array_push($blocks, $temp);

    		$showFarms = Showfarm::where('block_id', $value->id)->get();
    		if(!empty($showFarms)){
    			foreach ($showFarms as $key_ => $val_) {
    				$sf = [
    					"id" => (int)$val_->id,
    					"block_id" => (int)$val_->block_id,
    					"feature_id" => (int)$val_->feature_id,
    					"type" => (string)$val_->type,
    					"created_at" => (string)$value->created_at,
    					"updated_at" => (string)$value->updated_at,
    				];

    				array_push($confBlock, $sf);

    			}
    		}
    	}

    	return json_encode(["typefeature" =>  $typefeature, "feature" => $feature, "block" => $blocks, "blocksvarity" => $blocksvarity, "varities" => $varities, "char" => $characteristics, "showFarms" => $confBlock]);

    }

    public  function in_array_field($needle, $needle_field, $haystack, $strict = false) {
	    if ($strict) {
	        foreach ($haystack as $item)
	            if (isset($item->$needle_field) && $item->$needle_field === $needle)
	                return true;
	    }
	    else {
	        foreach ($haystack as $item)
	            if (isset($item->$needle_field) && $item->$needle_field == $needle)
	                return true;
	    }
	    return false;
	}

    public function import(Request $request){		

    }

    public function featuresCatalog()
    {
        $features = Feature::with(['typefeature', 'values'])
            ->orderBy('typefeature_id')
            ->orderBy('id')
            ->get();

        return response()->json([
            'count' => $features->count(),
            'data' => $features->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->name,
                    'year' => $feature->year,
                    'type' => $feature->type,
                    'slug' => $feature->slug,
                    'repeat_do' => $feature->repeat_do,
                    'frying_test' => $feature->frying_test,
                    'localities_viewer' => $feature->localities_viewer,
                    'typefeature_id' => $feature->typefeature_id,
                    'typefeature' => $feature->typefeature ? [
                        'id' => $feature->typefeature->id,
                        'name' => $feature->typefeature->name,
                        'names' => $feature->typefeature->names,
                    ] : null,
                    'values' => $feature->values->map(function ($value) {
                        return [
                            'id' => $value->id,
                            'name' => $value->name,
                            'value' => $value->value,
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }

    public function resultsQueryGuide()
    {
        $families = (new FeatureGuideBuilder())->build();

        return response()->json([
            'module' => 'results',
            'purpose' => 'Guia estructurada para que un agente IA externo entienda como consultar resultados en la plataforma.',
            'search_endpoint' => url('/api/results/search'),
            'source_interface' => url('/results'),
            'rules' => [
                'Las caracteristicas tipo 1 se consultan marcando uno o varios valores en data[feature_id][valor].',
                'En caracteristicas tipo 1, si se marcan multiples valores numericos, el buscador usa el minimo y maximo seleccionados como rango.',
                'Las caracteristicas tipo 2 representan una decision binaria Si/No.',
                'Las caracteristicas tipo 3 usan operator[feature_id] junto con data[feature_id].',
                'Los operadores admitidos para tipo 3 son ==, !=, >, < y rango manual con - usando el formato value1;value2.',
                'Si localities_viewer es 0, la consulta se evalua siempre contra la localidad comun 1.',
                'Las caracteristicas 48, 49, 50 y 51 consultan la tabla Fries_avg_characteristics; el resto consulta Avg_characteristic.',
                'El endpoint /api/results/search devuelve results, parents, results_count y parents_count.',
            ],
            'operators' => [
                'type_1' => [
                    'mode' => 'selection_range',
                    'accepted' => ['Seleccion directa', 'Rango por seleccion'],
                    'payload_examples' => [
                        'data[1][7]=7&data[1][9]=9',
                        'data[63][R]=R&data[63][RR]=RR',
                    ],
                ],
                'type_2' => [
                    'mode' => 'binary',
                    'accepted' => ['Si', 'No'],
                    'payload_examples' => [
                        "data[25]['si']=1",
                        "data[25]['no']=0",
                    ],
                ],
                'type_3' => [
                    'mode' => 'logical_operator',
                    'accepted' => ['==', '!=', '>', '<', '[value1 ; value2]'],
                    'payload_examples' => [
                        'operator[16]==&data[16]=60',
                        'operator[33]=>&data[33]=30',
                        'operator[33]=-&data[33]=20;40',
                    ],
                ],
            ],
            'request_examples' => [
                [
                    'name' => 'Rango de madurez 7 a 9',
                    'query' => '/api/results/search?type=1&data%5B1%5D%5B7%5D=7&data%5B1%5D%5B9%5D=9',
                ],
                [
                    'name' => 'Operador mayor que en caracteristica numerica',
                    'query' => '/api/results/search?operator%5B33%5D=%3E&data%5B33%5D=30',
                ],
                [
                    'name' => 'Rango manual en caracteristica tipo 3',
                    'query' => '/api/results/search?operator%5B16%5D=-&data%5B16%5D=20;60',
                ],
            ],
            'families_count' => $families->count(),
            'features_count' => $families->sum(function ($family) {
                return count($family['features']);
            }),
            'families' => $families,
        ]);
    }

    public function searchResults(Request $request)
    {
        $varity_array = [];
        $varity_list = [];
        $fries_list = [48,49,50,51];
        $locality_id = isset($request->locality_id) ? $request->locality_id : 1;
        $locality_original = $locality_id;
        $keys_ = [];
        $fathers_list = [];
        $mothers_list = [];

        if (!function_exists('array_key_last')) {
            function array_key_last(array $array) {
                if (!empty($array)) return key(array_slice($array, -1, 1, true));
            }
        }

        $featureIds = collect($request->data ?? [])->keys()->map(fn ($id) => (int) $id)->all();
        $featuresById = Feature::with('typefeature')->whereIn('id', $featureIds)->get()->keyBy('id');

        if ($request->block && !isset($request->only_childrens)) {
            $block_varity = Varity::whereHas('blocksvarity', function (Builder $query) use ($request) {
                $query->whereBlock_id($request->block);
            })->get();

            foreach ($block_varity as $value_) {
                $varity_array[$value_->id][$value_->feature_id]['promedio'] = $value_->PROMEDIO;
                $varity_list[] = $value_->id;
            }
        }

        if (isset($request->only_childrens)) {
            if ($request->block) {
                $varity_list = [];
                $varity_array = [];
                $fathers_list = [];
                $mothers_list = [];
            }

            $childrens = Varity::where('father_id', $request->varities_list)
                ->orWhere('mother_id', $request->varities_list)
                ->get();

            foreach ($childrens as $value_) {
                if (!empty($value_->name)) {
                    $varity_array[$value_->id][$value_->feature_id]['promedio'] = $value_->PROMEDIO;
                    $varity_list[] = $value_->id;
                }
            }
        }

        foreach (($request->data ?? []) as $key => $value) {
            $feature = $featuresById->get((int) $key);
            if (!$feature) {
                continue;
            }

            $locality_id = $feature->localities_viewer == 0 ? 1 : $locality_original;
            $selectedValues = $this->normalizeSelectedValues($value);
            $isDiscreteTextSelection = is_array($value)
                && !empty($selectedValues)
                && !$this->areAllSelectedValuesNumeric($selectedValues)
                && !isset($request->operator[$key]);

            if (is_array($value)) {
                if (isset($request->operator[$key]) && $request->operator[$key] == '-') {
                    $pos = strpos($value, ';');
                    if ($pos !== false) {
                        $pos = explode(';', $value);
                        $max = $pos[1];
                        $min = $pos[0];
                    } else {
                        $max = array_keys($value, max($value))[0];
                        $min = array_keys($value, min($value))[0];
                    }
                } else {
                    $max = array_keys($value, max($value))[0];
                    $min = array_keys($value, min($value))[0];
                }
            } else {
                $pos = strpos($value, ';');
                if ($pos !== false) {
                    $pos = explode(';', $value);
                    $max = $pos[1];
                    $min = $pos[0];
                } else {
                    $max = null;
                    $min = $value;
                }
            }

            $keys_[$key] = $isDiscreteTextSelection ? implode(', ', $selectedValues) : $max;

            if (!empty($varity_array)) {
                $max == null ? $max = 0 : '';
                $query = Avg_characteristic::where('locality', $locality_id)->where('feature_id', $key)->whereNull('deleted_at');

                if (is_numeric($max) && is_numeric($min) && $max == 0 && $min == 0) {
                    $varities = Varity::whereNotNull('name')->whereIn('id', $varity_list)->whereDoesntHave('characteristics', function (Builder $query) use ($key) {
                        $query->where('feature_id', $key);
                    })->get();

                    $varity_list = [];
                    foreach ($varities as $value_) {
                        $varity_array[$value_->id][$key]['promedio'] = 0;
                        $varity_list[] = $value_->id;
                    }
                    break;
                } elseif (is_numeric($max) && is_numeric($min) && $max > 0 && $max != $min) {
                    $varities = $query->whereBetween('PROMEDIO', [$min, $max])->whereIn('varity_id', $varity_list);
                } else {
                    if (isset($request->operator[$key])) {
                        $varities = $query->where('PROMEDIO', $request->operator[$key], (int) $min)->whereIn('varity_id', $varity_list);
                    } else {
                        if (is_numeric($min)) {
                            $varities = $query->where('PROMEDIO', (int) $min)->whereIn('varity_id', $varity_list);
                        }
                        if (is_string($min)) {
                            if ($isDiscreteTextSelection) {
                                $varities = $query->whereIn('PROMEDIO', $selectedValues)->whereIn('varity_id', $varity_list);
                            } else {
                                $varities = $query->where('PROMEDIO', 'like', $min)->whereIn('varity_id', $varity_list);
                            }
                        }
                    }
                }

                $varity_list = [];
                foreach ($varities->get() as $value_) {
                    if ($value_->feature_id == $key) {
                        $varity_array[$value_->varity_id][$value_->feature_id]['promedio'] = $value_->PROMEDIO;
                        $varity_list[] = $value_->varity_id;
                    }
                }
            } else {
                $query = Avg_characteristic::where('locality', $locality_id)->where('feature_id', $key)->whereNull('deleted_at');

                if (is_numeric($max) && is_numeric($min) && $max == 0 && $min == 0) {
                    $varities = Varity::whereNotNull('name')->whereDoesntHave('characteristics', function (Builder $query) use ($key) {
                        $query->where('feature_id', $key);
                    })->get();

                    foreach ($varities as $value_) {
                        $varity_array[$value_->id][$key]['promedio'] = 0;
                        $varity_array[$value_->id][$key]['ponderado'] = 0;
                        $varity_list[] = $value_->id;
                    }
                } elseif (is_numeric($max) && is_numeric($min) && $max > 0 && $max != $min) {
                    if (!in_array($key, $fries_list)) {
                        $varities = $query->whereBetween('PROMEDIO', [$min, $max])->get();
                        foreach ($varities as $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]['promedio'] = $value_->PROMEDIO;
                            $varity_list[] = $value_->varity_id;
                        }
                    } else {
                        $varities = Fries_avg_characteristics::where('feature_id', $key)->whereBetween('PROMEDIO', [$min, $max])->get();
                        foreach ($varities as $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]['promedio'] = $value_->PROMEDIO;
                            $varity_array[$value_->varity_id][$value_->feature_id]['ponderado'] = $value_->ponderado;
                            $varity_list[] = $value_->varity_id;
                        }
                    }
                } else {
                    if (isset($request->operator[$key])) {
                        $varities = $query->where('PROMEDIO', $request->operator[$key], (int) $min);
                    } else {
                        if (is_numeric($min)) {
                            $varities = in_array($key, $fries_list)
                                ? Fries_avg_characteristics::where('feature_id', $key)->whereBetween('PROMEDIO', [$min, $max])
                                : $query->where('PROMEDIO', (int) $min);
                        } elseif (is_string($min)) {
                            if ($isDiscreteTextSelection) {
                                $selectedValues = array_map(function ($selectedValue) {
                                    return str_replace("'", '', $selectedValue);
                                }, $selectedValues);
                                $varities = $query->whereIn('PROMEDIO', $selectedValues);
                            } else {
                                $min = str_replace("'", '', $min);
                                $max = str_replace("'", '', $max);
                                $varities = $query->where('PROMEDIO', 'like', $min);
                                if ($max != null && $max != $min) {
                                    $varities->orWhere('PROMEDIO', 'like', $max);
                                }
                            }
                        }
                    }

                    foreach ($varities->get() as $value_) {
                        $varity_array[$value_->varity_id][$value_->feature_id]['promedio'] = $value_->PROMEDIO;
                        if (in_array($key, $fries_list)) {
                            $varity_array[$value_->varity_id][$value_->feature_id]['ponderado'] = $value_->ponderado;
                        }
                        $varity_list[] = $value_->varity_id;
                    }
                }
            }
        }

        $varities = array_values(array_unique($varity_list));
        $result_models = Varity::withTrashed()->with(['father', 'mother'])->whereIn('id', $varities)->get()->keyBy('id');

        $parent_counts = [];
        foreach ($result_models as $result_model) {
            if (!empty($result_model->father_id)) {
                $parent_counts[$result_model->father_id] = ($parent_counts[$result_model->father_id] ?? 0) + 1;
            }
            if (!empty($result_model->mother_id)) {
                $mothers_list[$result_model->mother_id] = ($mothers_list[$result_model->mother_id] ?? 0) + 1;
            }
        }

        $parent_models = Varity::withTrashed()->with(['father', 'mother'])->whereIn('id', array_keys($parent_counts))->get()->keyBy('id');

        $results = collect($varities)->map(function ($varityId) use ($result_models, $varity_array, $keys_, $featuresById) {
            $model = $result_models->get($varityId);
            if (!$model) {
                return null;
            }

            return [
                'id' => $model->id,
                'name' => $this->sanitizeVarityLabel($model->name),
                'code' => $this->sanitizeVarityLabel($model->code),
                'year' => $model->year,
                'mother_id' => $model->mother_id,
                'father_id' => $model->father_id,
                'mother_name' => $this->sanitizeVarityLabel($model->motherName()),
                'father_name' => $this->sanitizeVarityLabel($model->fatherName()),
                'features' => collect($keys_)->map(function ($filterValue, $featureId) use ($varity_array, $varityId, $featuresById) {
                    return [
                        'feature_id' => (int) $featureId,
                        'feature_name' => optional($featuresById->get((int) $featureId))->name,
                        'filter_value' => $filterValue,
                        'promedio' => data_get($varity_array, $varityId.'.'.$featureId.'.promedio'),
                        'ponderado' => data_get($varity_array, $varityId.'.'.$featureId.'.ponderado'),
                    ];
                })->values(),
            ];
        })->filter()->values();

        $parents = collect($parent_counts)->map(function ($count, $parentId) use ($parent_models) {
            $model = $parent_models->get($parentId);
            if (!$model) {
                return null;
            }

            return [
                'id' => $model->id,
                'name' => $this->sanitizeVarityLabel($model->name),
                'code' => $this->sanitizeVarityLabel($model->code),
                'year' => $model->year,
                'references' => $count,
                'mother_name' => $this->sanitizeVarityLabel($model->motherName()),
                'father_name' => $this->sanitizeVarityLabel($model->fatherName()),
            ];
        })->filter()->values();

        return response()->json([
            'results_count' => $results->count(),
            'parents_count' => $parents->count(),
            'locality_id' => $locality_id,
            'results' => $results,
            'parents' => $parents,
        ]);
    }
    			
}
