<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Block;
use App\Models\Mapout;
use App\Models\Varity;
use App\Models\Market;
use App\Models\Blocksvarity;
use App\Models\Typefeature;
use App\Models\Characteristic;
use App\Greenhouse;
use App\Models\Showfarm;
use App\Models\Feature;
use App\Models\Value;
use App\Locality;
use PDF;
use App\Avg_characteristic;



class ResultsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Vista de tipos de motor
     */
    public function index()
    {
        $markets = Market::pluck('name','id');
        $blocks_list  = Block::pluck('description','id');
        $years = Block::select('year')->distinct()->orderBy('year','desc')->pluck('year','year');
        $varities = Varity::select('name')->where('name', 'not like', '')->get();
        // $varities = Varity::select('name')->where('name', 'not like', '')->withTrashed()->get();
        #$blocks = Blocksvarity::all();
        $localities = Locality::pluck('name','id');

        return View('results.index',compact('blocks_list', 'markets', 'years','varities','localities'));
    }

    public function stadistics()
    {

        $valores = array("ADARA"=>array("57.2","55.4","58.2"),
                        "Asterix"=>array("48.9","54.3","52.5"),
                        "KAIA"=>array("56.5","57.0","61.0"),
                        "Karu"=>array("54.1","58.0","60.3"),
                        "Pukara"=>array("48.7","56.0","53.2"),
                        "TRAUKO"=>array("58.7","62.3","56.0")
                    );

        $varities = Varity::select('name')->where('name', '!=', '')->get();


        return View('stadistics.index',array("valores"=>$valores, "varities" => $varities));
    }

    public function fries(){
        $markets = Market::pluck('name','id');
        $blocks_list  = Block::pluck('description','id');
        $years = Block::select('year')->distinct()->orderBy('year','desc')->pluck('year','year');
        $varities = Varity::select('name')->where('name', '!=', '')->get();

        return View('results.fries.index',compact('blocks_list', 'markets', 'years','varities'));
    }

    public function crossings(){

        $greenhouse  = Greenhouse::pluck('name','id');

        return View('results.crossings.index', compact('greenhouse'));
    }

    public function getDataLegacy(Request $request){
        $status = false;
        $res  = Blocksvarity::all();
        $data = [];
        $temp = [];
        $values = [];

        if(isset($request->block)){
            $blockIds = $this->normalizeRequestIds($request->input('block'));
            $res = !empty($blockIds) ? Blocksvarity::whereIn('block_id', $blockIds)->get() : Blocksvarity::all();
        }

        foreach ($res as $key => $value) {

            if($value->varity){

                $temp = array(
                    "code"   => @$value->varity->name,
                    "block"  => @$value->block->description,
                    "season" => $value->varity->year,
                    //"season_block" => $value->year,
                    //"father" => @$value->varity->father()->get()[0]->name,
                    //"mother" => @$value->varity->mother()->get()[0]->name,
                    //"market" => @$value->varity->market->name,
                    //'berries' => $value->varity->berries,
                    //'type' => $value->type,
                    //'block_id' => $value->id
                );

                foreach ($request->arr_caracteristicas as $k => $v) {


                    $avg = "-";
                    $avg_count = 0;

                    $c1 = Characteristic::where('varity_id',$value->varity->id)->where('feature_id',$v['IDFEATURE'])->where('year',0)->first();
                    $c1 = (!empty($c1)) ? $c1->value:null;
                    $c2 = Characteristic::where('varity_id',$value->varity->id)->where('feature_id',$v['IDFEATURE'])->where('year',1)->first();
                    $c2 = (!empty($c2)) ? $c2->value:null;

                    $c3 = Characteristic::where('varity_id',$value->varity->id)->where('feature_id',$v['IDFEATURE'])->where('year',2)->first();
                    $c3 = (!empty($c3)) ? $c3->value:null;
                    $c4 = Characteristic::where('varity_id',$value->varity->id)->where('feature_id',$v['IDFEATURE'])->where('year',3)->first();
                    $c4 = (!empty($c4)) ? $c4->value:null;
                    $c5 = Characteristic::where('varity_id',$value->varity->id)->where('feature_id',$v['IDFEATURE'])->where('year',4)->first();
                    $c5 = (!empty($c5)) ? $c5->value:null;
                    if($c1 != "") $avg_count++;
                    if($c2 != "") $avg_count++;
                    if($c3 != "") $avg_count++;
                    if($c4 != "") $avg_count++;
                    if($c5 != "") $avg_count++;

                    if(is_numeric($c1)){
                        $avg = $c1 + $c2 + $c3 + $c4 + $c5;
                        $avg = $avg/$avg_count;
                    }else{
                        if(is_null($c1)){
                            $c1 = "-";
                        }
                        $avg = $c1;
                    }


                    $temp += ["v".$v['IDFEATURE'] => $avg];

                }
                $temp += ["state"  => @$value->state];
                $temp += ["varity_id"  => @$value->varity->id];
                $temp += ["type"  => @$value->type];
                $temp += ["block_id"  => @$value->block_id];

                array_push($data, $temp);
            }
        }
        !empty($data) ? $status =  true: "";

        return json_encode(["data" => $data,"status" => $status]);
    }

    public function getData(Request $request){
        $status = false;
        $data = [];

        $blocksQuery = Blocksvarity::query()->with(['varity', 'block']);
        if(isset($request->block)){
            $blockIds = $this->normalizeRequestIds($request->input('block'));
            if(!empty($blockIds)){
                $blocksQuery->whereIn('block_id', $blockIds);
            }
        }

        $blocks = $blocksQuery->get();
        $featureIds = collect($request->arr_caracteristicas ?? [])->pluck('IDFEATURE')->filter()->values()->all();
        $varityIds = $blocks->pluck('varity_id')->filter()->unique()->values()->all();

        $characteristics = Characteristic::query()
            ->whereIn('varity_id', $varityIds)
            ->whereIn('feature_id', $featureIds)
            ->whereIn('year', [0, 1, 2, 3, 4])
            ->get()
            ->groupBy(function ($item) {
                return $item->varity_id.'|'.$item->feature_id;
            });

        foreach ($blocks as $value) {
            if(!$value->varity){
                continue;
            }

            $temp = array(
                "code"   => @$value->varity->name,
                "block"  => @$value->block->description,
                "season" => $value->varity->year,
            );

            foreach ($request->arr_caracteristicas as $v) {
                $charGroup = $characteristics->get($value->varity->id.'|'.$v['IDFEATURE'], collect());
                $avg = "-";
                $avg_count = 0;

                $c1 = optional($charGroup->firstWhere('year', 0))->value;
                $c2 = optional($charGroup->firstWhere('year', 1))->value;
                $c3 = optional($charGroup->firstWhere('year', 2))->value;
                $c4 = optional($charGroup->firstWhere('year', 3))->value;
                $c5 = optional($charGroup->firstWhere('year', 4))->value;

                if($c1 != "") $avg_count++;
                if($c2 != "") $avg_count++;
                if($c3 != "") $avg_count++;
                if($c4 != "") $avg_count++;
                if($c5 != "") $avg_count++;

                if(is_numeric($c1)){
                    $avg = (($c1 ?? 0) + ($c2 ?? 0) + ($c3 ?? 0) + ($c4 ?? 0) + ($c5 ?? 0));
                    $avg = $avg_count > 0 ? $avg / $avg_count : "-";
                }else{
                    if(is_null($c1)){
                        $c1 = "-";
                    }
                    $avg = $c1;
                }

                $temp += ["v".$v['IDFEATURE'] => $avg];
            }

            $temp += ["state"  => @$value->state];
            $temp += ["varity_id"  => @$value->varity->id];
            $temp += ["type"  => @$value->type];
            $temp += ["block_id"  => @$value->block_id];

            $data[] = $temp;
        }

        !empty($data) ? $status =  true: "";

        return json_encode(["data" => $data,"status" => $status]);
    }

    public function getDataBenchmark(Request $request){
        $startedLegacy = microtime(true);
        $legacy = json_decode($this->getDataLegacy($request), true);
        $legacyMs = round((microtime(true) - $startedLegacy) * 1000, 2);

        $startedOptimized = microtime(true);
        $optimized = json_decode($this->getData($request), true);
        $optimizedMs = round((microtime(true) - $startedOptimized) * 1000, 2);

        return response()->json([
            'legacy' => [
                'count' => isset($legacy['data']) ? count($legacy['data']) : 0,
                'ms' => $legacyMs,
                'status' => $legacy['status'] ?? false,
            ],
            'optimized' => [
                'count' => isset($optimized['data']) ? count($optimized['data']) : 0,
                'ms' => $optimizedMs,
                'status' => $optimized['status'] ?? false,
            ],
            'same_count' => (isset($legacy['data']) ? count($legacy['data']) : 0) === (isset($optimized['data']) ? count($optimized['data']) : 0),
        ]);
    }

    public function getProm($characteristic){

        return $prom;
    }

    public function getCaracteristicas(){

        $crossing = Varity::find($_GET['crossing']);
        $type = $_GET['type'];

        if(isset($_GET['unique'])){
            if($_GET['unique'] != ""){

                $f = Feature::find($_GET['unique']);
                $block = "";
                $typesfeatures = $f->typefeature;

                @$valor0 = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',0)->first()->value;
                @$valor1 = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',1)->first()->value;
                @$valor2 = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',2)->first()->value;
                @$valor3 = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',3)->first()->value;
                @$valor4 = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',4)->first()->value;


                return View('results.getCaracteristicas',compact('crossing','typesfeatures','block','type', 'f','valor0','valor1','valor2','valor3','valor4'));

            }
        }


        $block = Block::find($_GET['block_id']);
        $typesfeatures = Typefeature::All();

        foreach ($typesfeatures as $t_key => $t) {
            foreach ($t->features as $key => $f) {
               // if(Showfarm::where('block_id',$block->id)->count() == 0){
                #AÑO 0
                @$characteristic = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',0)->first();
                @$typesfeatures[$t_key]->features[$key]->valor0 = (!empty($characteristic)) ? $characteristic->value:null;
                @$typesfeatures[$t_key]->features[$key]->value_repeats_0 = (!empty($characteristic)) ? @$characteristic->repeats_values:null;
                #AÑO 1
                @$characteristic = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',1)->first();
                @$typesfeatures[$t_key]->features[$key]->valor1 = (!empty($characteristic)) ? $characteristic->value:null;
                @$typesfeatures[$t_key]->features[$key]->value_repeats_1 = (!empty($characteristic)) ? @$characteristic->repeats_values:null;
                #AÑO 2
                @$characteristic = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',2)->first();
                @$typesfeatures[$t_key]->features[$key]->valor2 = (!empty($characteristic)) ? $characteristic->value:null;
                @$typesfeatures[$t_key]->features[$key]->value_repeats_2 = (!empty($characteristic)) ? @$characteristic->repeats_values:null;
                #AÑO 3
                @$characteristic = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',3)->first();
                @$typesfeatures[$t_key]->features[$key]->valor3 = (!empty($characteristic)) ? $characteristic->value:null;
                @$typesfeatures[$t_key]->features[$key]->value_repeats_3 = (!empty($characteristic)) ? @$characteristic->repeats_values:null;
                #AÑO 4
                @$characteristic = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',4)->first();
                @$typesfeatures[$t_key]->features[$key]->valor4 = (!empty($characteristic)) ? $characteristic->value:null;
                @$typesfeatures[$t_key]->features[$key]->value_repeats_4 = (!empty($characteristic)) ? @$characteristic->repeats_values:null;
                /*}else{
                    unset($t->features[$key]);
                }*/
            }

        }

        return View('results.getCaracteristicas',compact('crossing','typesfeatures','block','type'));
    }

    public function modCaracteristicas(){

        $typesfeatures = Typefeature::All();


        return View('results.modCaracteristicas',compact('typesfeatures'));
    }

    public function preview_report(){
        $markets = Market::pluck('name','id');
        $blocks_list  = Block::pluck('description','id');
        $years = Block::select('year')->distinct()->orderBy('year','desc')->pluck('year','year');
        
        // 1. Descomentar y quitar el select('name') para traer id, father_name y mother_name
        $varities = Varity::where('name', 'not like', '')->get(); 
        
        $localities = Locality::pluck('name','id');

        // 2. ¡MUY IMPORTANTE! Agregar 'varities' adentro del compact()
        return View('results.reports.preview_report',compact('markets','blocks_list', 'years', 'localities', 'varities'));
    }

    public function varities(Request $request){

        $block_data = Blocksvarity::whereBlock_id($request->block_id)->with('varity')->get();
        $block_data = $block_data->pluck('varity')->filter()->unique();

        return json_encode($block_data);
    }

    public function load_preview(Request $request){

        $data = [];

        foreach ($request->array_varity as $key => $value) {

            $varity = Varity::whereId($value['IDVARITY'])->first();
            $typefeaturearray=Array();
            $typefselector=Array();
            if($varity){

                $temp = array(
                    "id" =>  $varity->id,
                    "code"   => @$varity->name,
                    "season" => $varity->year,
                    "resultados" => [],
                    "multimedia" => $varity->multimedias
                );


                foreach ($request->array_find as $k => $v) {


                    $avg = "-";
                    $std = 0;
                    $resultado="";

                    $typefname = Feature::find($v['IDFEATURE']);
                    $char = Avg_characteristic::whereVarity_id($varity->id)->whereFeature_id($v['IDFEATURE'])->first();

                    if(count($temp['resultados']) == 0 ){
                        $first_char = $typefname->typefeature->name;
                    }else{
                        $first_char = "";
                    }

                    $temp['resultados'][] = [ "PROM" => @$char->PROMEDIO? $char->PROMEDIO : 0,
                                              "STD" => @$char->STD ? $char->STD : "-",
                                              'NAME' => @$typefname->name? $typefname->name : 0,
                                              "VAL" => "",
                                              "TYPE" => $first_char];

                    array_push($typefeaturearray,$typefname->typefeature->name);
                }

                array_push($data, $temp);
            }
        }
        return View('results.reports.preview_pdf', compact('data','typefeaturearray'));
    }

    function stats_standard_deviation(array $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
           --$n;
        }
        return sqrt($carry / $n);
    }

    public function report(Request $request){
        $data = json_decode($_POST["data"]);

        $pdf = PDF::loadView('results.reports.report', compact('data'));
        //Aqui guardo el reporte.
        //Storage::put($sample->report_route, $pdf->output());

        //Descargo el reporte
        return $pdf->stream('pdf');
    }

    public function info_to_compare(Request $request){
        $data    = [];
        $headers = [];
        $resultado = [];

        #$f = Feature::OrderBy('typefeature_id')->OrderBy('name', 'asc')->pluck('name','id')->toArray();
        $f = Feature::OrderBy('name', 'asc')->get();

        foreach ($f as $key => $value) {
            if(!isset($data[$value->id])){
                $data[$value->id] = array(
                    "NAME" => $value
                );
            }
        }

        $locality_ori = isset($request->locality) ? $request->locality : 1;

        foreach ($request->selected_p ?? [] as $k => $v) {
            foreach ($data as $key => $value) {
                $std = 0;
                $f = Feature::find($key)->localities_viewer;
                $locality = $f == 0 ? 1 : $locality_ori;
                $c = Characteristic::where('varity_id', $v["IDVARITY"])->where('feature_id', $key)->where('locality_id', $locality)->where('value', 'not like', '0')->get();
                    $res = "-";

                    if($c->count() > 0){
                        if($c[0]->value > 0){
                            $res = round(@$c->avg('value'), 2);
                            $std = @$this->stats_standard_deviation_model($c);
                        }else{
                            $res = $c[0]->value;
                        }
                    }

                    if(!isset($data[$v["IDVARITY"]])){
                        $data[$key][$v["IDVARITY"]] = array(
                            "value" => $res,
                            "std" => $std,
                            "num" => count($c)
                        );
                    }
                /*
                if($locality == 1){
                    $c = Characteristic::where('varity_id', $v["IDVARITY"])->where('feature_id', $key)->where('locality_id', $locality)->where('value', 'not like', '0')->get();
                    $res = "-";

                    if($c->count() > 0){
                        if($c[0]->value > 0){
                            $res = round(@$c->avg('value'), 2);
                            $std = @$this->stats_standard_deviation_model($c);
                        }else{
                            $res = $c[0]->value;
                        }
                    }

                    if(!isset($data[$v["IDVARITY"]])){
                        $data[$key][$v["IDVARITY"]] = array(
                            "value" => $res,
                            "std" => $std,
                            "num" => count($c)
                        );
                    }
                }else{
                    $c = Characteristic::where('varity_id', $v["IDVARITY"])->where('feature_id', $key)->where('value', 'not like', '0')->groupBy('locality_id')->get();
                    $res = "-";

                    if($c->count() > 0){
                        if($c[0]->value > 0){
                            $res = round(@$c->avg('value'), 2);
                            $std = @$this->stats_standard_deviation_model($c);
                        }else{
                            $res = $c[0]->value;
                        }
                    }

                    $data[$key][$v["IDVARITY"]][] = array(
                        "value" => $res,
                        "std" => $std,
                        "num" => count($c)
                    );
                }
                */
            }
        }


        return json_encode(["headers" => $headers, "data" => $data]);
    }

    public function compareBetaBootstrap()
    {
        $configs = DB::table('compare_feature_configs')
            ->orderBy('name', 'asc')
            ->get();

        $configFeatures = DB::table('compare_feature_config_features')
            ->orderBy('position', 'asc')
            ->get()
            ->groupBy('config_id');

        $configs = $configs->map(function ($config) use ($configFeatures) {
            $config->feature_ids = $configFeatures->get($config->id, collect())
                ->pluck('feature_id')
                ->map(function ($id) {
                    return (int) $id;
                })
                ->values();

            return $config;
        });

        $groups = Typefeature::with(['features' => function ($query) {
                $query->where('status', 1)->orderBy('name', 'asc');
            }])
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'features' => $group->features->map(function ($feature) {
                        return [
                            'id' => $feature->id,
                            'name' => $feature->name,
                        ];
                    })->values(),
                ];
            });

        return response()->json([
            'configs' => $configs,
            'groups' => $groups,
            'actions' => DB::table('type_compare')->orderBy('nombre', 'asc')->get(),
            'descriptions' => DB::table('compare_descriptions')->orderBy('nombre', 'asc')->get(),
        ]);
    }

    public function saveCompareBetaConfig(Request $request)
    {
        $name = trim((string) $request->input('name'));
        $featureIds = collect($request->input('feature_ids', []))
            ->map(function ($id) {
                return (int) $id;
            })
            ->filter()
            ->unique()
            ->values();

        if ($name === '') {
            return response()->json(['status' => false, 'message' => 'Nombre requerido'], 422);
        }

        $configId = DB::transaction(function () use ($request, $name, $featureIds) {
            $selectedConfigId = (int) $request->input('id');
            $selectedConfig = $selectedConfigId > 0
                ? DB::table('compare_feature_configs')->where('id', $selectedConfigId)->first()
                : null;

            if ($selectedConfig && trim((string) $selectedConfig->name) === $name) {
                $configId = $selectedConfig->id;

                DB::table('compare_feature_configs')
                    ->where('id', $configId)
                    ->update(['updated_at' => now()]);
            } else {
                $existing = DB::table('compare_feature_configs')->where('name', $name)->first();

                if ($existing) {
                    $configId = $existing->id;

                    DB::table('compare_feature_configs')
                        ->where('id', $configId)
                        ->update(['updated_at' => now()]);
                } else {
                    $configId = DB::table('compare_feature_configs')->insertGetId([
                        'name' => $name,
                        'user_id' => auth()->id(),
                        'is_default' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('compare_feature_config_features')->where('config_id', $configId)->delete();

            foreach ($featureIds as $position => $featureId) {
                DB::table('compare_feature_config_features')->insert([
                    'config_id' => $configId,
                    'feature_id' => $featureId,
                    'position' => $position + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $configId;
        });

        return response()->json([
            'status' => true,
            'config_id' => $configId,
        ]);
    }

    public function compareBetaData(Request $request)
    {
        $featureIds = collect($request->input('feature_ids', []))
            ->map(function ($id) {
                return (int) $id;
            })
            ->filter()
            ->unique()
            ->values();

$selected = collect($request->input('selected_p', []))
->filter(function ($item) {
$varityId = (int) ($item['IDVARITY'] ?? 0);
return $varityId > 0;
})
->values();
        $localityOri = $request->input('locality') ?: 1;

        $features = Feature::whereIn('id', $featureIds)->get()->keyBy('id');
        $orderedFeatures = $featureIds->map(function ($featureId) use ($features) {
            return $features->get($featureId);
        })->filter();

        $rows = [];

        foreach ($selected as $item) {
            $varityId = (int) ($item['IDVARITY'] ?? 0);
            $varity = Varity::withTrashed()->find($varityId);

            if (!$varity) {
                continue;
            }

            $row = [
                'id' => $varity->id,
                'varity' => $this->varityDisplayName($varity),
                'father' => $varity->fatherName(),
                'mother' => $varity->motherName(),
                'features' => [],
            ];

            foreach ($orderedFeatures as $feature) {
                $row['features'][$feature->id] = $this->compareBetaFeatureValue($varity->id, $feature, $localityOri);
            }

            $rows[] = $row;
        }

        return response()->json([
            'features' => $orderedFeatures->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->name,
                ];
            })->values(),
            'rows' => $rows,
        ]);
    }

    private function varityDisplayName($varity)
    {
        $name = trim(strip_tags((string) @$varity->name));
        if ($name !== '') {
            return $name;
        }

        $code = trim((string) @$varity->code);
        if ($code !== '') {
            return $code;
        }

        return '#'.$varity->id;
    }

    private function compareBetaFeatureValue($varityId, $feature, $localityOri)
    {
        $locality = ((int) $feature->localities_viewer) === 0 ? 1 : $localityOri;

        $characteristics = Characteristic::where('varity_id', $varityId)
            ->where('feature_id', $feature->id)
            ->where('locality_id', $locality)
            ->where('value', 'not like', '0')
            ->get();

        if ($characteristics->count() === 0) {
            return [
                'value' => '-',
                'std' => 0,
                'num' => 0,
            ];
        }

        $numericValues = $characteristics->map(function ($characteristic) {
            return $this->normalizeCompareBetaNumericValue($characteristic->value);
        })->filter(function ($value) {
            return $value !== null;
        })->values();

        if ($numericValues->count() > 0) {
            return [
                'value' => round($numericValues->avg(), 2),
                'std' => $this->compareBetaStandardDeviation($numericValues),
                'num' => $characteristics->count(),
            ];
        }

        return [
            'value' => $characteristics[0]->value,
            'std' => 0,
            'num' => $characteristics->count(),
        ];
    }

    private function normalizeCompareBetaNumericValue($value)
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $normalized = str_replace(',', '.', $value);

        if (!preg_match('/^-?[0-9]+(\\.[0-9]+)?$/', $normalized)) {
            return null;
        }

        return (float) $normalized;
    }

    private function compareBetaStandardDeviation($values, $sample = false)
    {
        $count = $values->count();

        if ($count === 0 || ($sample && $count === 1)) {
            return 0;
        }

        $mean = $values->avg();
        $carry = 0.0;

        foreach ($values as $value) {
            $diff = $value - $mean;
            $carry += $diff * $diff;
        }

        if ($sample) {
            $count--;
        }

        return round(sqrt($carry / $count), 2);
    }

    public function saveCompareBetaAction(Request $request)
    {
        $name = trim((string) $request->input('nombre'));

        if ($name === '') {
            return response()->json(['status' => false, 'message' => 'Nombre requerido'], 422);
        }

        $existing = DB::table('type_compare')->where('nombre', $name)->first();

        if ($existing) {
            DB::table('type_compare')->where('id', $existing->id)->update(['updated_at' => now()]);
            $id = $existing->id;
        } else {
            $id = DB::table('type_compare')->insertGetId([
                'nombre' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'status' => true,
            'action' => DB::table('type_compare')->where('id', $id)->first(),
            'actions' => DB::table('type_compare')->orderBy('nombre', 'asc')->get(),
        ]);
    }

    public function saveCompareBetaSnapshot(Request $request)
    {
        $title = trim((string) $request->input('nombre'));
        $details = collect($request->input('details', []));
        $featureIds = collect($request->input('feature_ids', []))
            ->map(function ($id) {
                return (int) $id;
            })
            ->filter()
            ->unique()
            ->values();

        if ($title === '') {
            return response()->json(['status' => false, 'message' => 'Título requerido'], 422);
        }

        if ($details->count() === 0) {
            return response()->json(['status' => false, 'message' => 'Debe existir al menos una variedad'], 422);
        }

        $compareId = DB::transaction(function () use ($request, $title, $details, $featureIds) {
            $compareId = (int) $request->input('compare_id');

            if ($compareId > 0 && DB::table('compares')->where('id', $compareId)->whereNull('deleted_at')->exists()) {
                DB::table('compares')
                    ->where('id', $compareId)
                    ->update([
                        'nombre' => $title,
                        'feature_ids' => json_encode($featureIds->values()),
                        'updated_at' => now(),
                    ]);
            } else {
                $compareId = DB::table('compares')->insertGetId([
                    'nombre' => $title,
                    'feature_ids' => json_encode($featureIds->values()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('compare_details')
                ->where('compare_id', $compareId)
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now(),
                ]);

            foreach ($details as $detail) {
                DB::table('compare_details')->insert([
                    'compare_id' => $compareId,
                    'rank' => $this->nullableCompareInt($detail['rank'] ?? null),
                    'description' => trim((string) ($detail['description'] ?? '')),
                    'free_description' => trim((string) ($detail['free_description'] ?? '')),
                    'id_varity' => (int) ($detail['id_varity'] ?? 0),
                    'id_type_action' => $this->nullableCompareInt($detail['id_type_action'] ?? null),
                    'feature_values' => json_encode($detail['feature_values'] ?? []),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $compareId;
        });

    return response()->json([
    'status' => true,
    'compare_id' => $compareId,
    ]);
    }

    public function compareResultsIndex()
    {
        $compares = DB::table('compares')
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('results.compare_results.index', compact('compares'));
    }

    public function compareResultsShow($id)
    {
        $compare = DB::table('compares')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$compare) {
            return response()->json(['status' => false, 'message' => 'Comparación no encontrada'], 404);
        }

        return response()->json([
            'status' => true,
            'compare' => $this->buildCompareResultsPayload($compare),
        ]);
    }

    public function compareResultsDestroy($id)
    {
        $exists = DB::table('compares')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->exists();

        if (!$exists) {
            return response()->json(['status' => false, 'message' => 'Comparación no encontrada'], 404);
        }

        DB::table('compares')
            ->where('id', $id)
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        DB::table('compare_details')
            ->where('compare_id', $id)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json(['status' => true]);
    }

    public function compareResultsExcel($id)
    {
        $compare = DB::table('compares')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$compare) {
            abort(404);
        }

        $payload = $this->buildCompareResultsPayload($compare);
        $filename = 'comparacion_resultados_'.$compare->id.'_'.date('Ymd_His').'.xls';
        $html = view('results.compare_results.excel', ['compare' => $payload])->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ]);
    }

    private function buildCompareResultsPayload($compare)
    {
        $featureIds = collect(json_decode($compare->feature_ids ?: '[]', true))
            ->map(function ($id) {
                return (int) $id;
            })
            ->filter()
            ->values();

        $features = Feature::whereIn('id', $featureIds)->get()->keyBy('id');
        $orderedFeatures = $featureIds->map(function ($featureId) use ($features) {
            return $features->get($featureId);
        })->filter()->values();

        $details = DB::table('compare_details')
            ->leftJoin('type_compare', 'compare_details.id_type_action', '=', 'type_compare.id')
            ->where('compare_details.compare_id', $compare->id)
            ->whereNull('compare_details.deleted_at')
            ->orderBy('compare_details.rank', 'asc')
            ->orderBy('compare_details.id', 'asc')
            ->select('compare_details.*', 'type_compare.nombre as action_name')
            ->get()
            ->map(function ($detail) use ($orderedFeatures) {
                $varity = Varity::withTrashed()->find($detail->id_varity);
                $values = json_decode($detail->feature_values ?: '[]', true);

                return [
                    'id' => $detail->id,
                    'rank' => $detail->rank,
                    'varity_id' => $detail->id_varity,
                    'varity' => $varity ? $this->varityDisplayName($varity) : '',
                    'father' => $varity ? $varity->fatherName() : '',
                    'mother' => $varity ? $varity->motherName() : '',
                    'free_description' => $detail->free_description,
                    'description' => $detail->description,
                    'action' => $detail->action_name,
                    'features' => $orderedFeatures->map(function ($feature) use ($values) {
                        $value = $values[$feature->id] ?? ['value' => '-'];

                        return [
                            'id' => $feature->id,
                            'name' => $feature->name,
                            'value' => $value['value'] ?? '-',
                            'std' => $value['std'] ?? '',
                            'num' => $value['num'] ?? '',
                        ];
                    })->values(),
                ];
            });

        return [
            'id' => $compare->id,
            'nombre' => $compare->nombre,
            'created_at' => $compare->created_at,
            'updated_at' => $compare->updated_at,
            'features' => $orderedFeatures->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->name,
                ];
            })->values(),
            'details' => $details,
        ];
    }

    private function nullableCompareInt($value)
    {
    if ($value === null || $value === '') {
    return null;
    }

    return (int) $value;
    }

    private function normalizeRequestIds($value)
    {
    if (!is_array($value)) {
    $value = [$value];
    }

    return array_values(array_unique(array_filter(array_map(function ($id) {
    return (int) $id;
    }, $value))));
    }

public function getVaritiesFromBlock(Request $request){
$varities = [];
$blockIds = $this->normalizeRequestIds($request->input('block', []));

if(empty($blockIds)){
return json_encode($varities);
}

$position = Blocksvarity::select('varity_id')->whereIn('block_id', $blockIds)->whereNotNull('varity_id')->distinct()->get();
foreach ($position as $key => $value) {
            $varity = Varity::withTrashed()->find($value->varity_id);
            $name = @$varity->name ? @$varity->name : @$varity->code;
            if(!empty($name)){
                array_push($varities, ["id" => $value->varity_id,"name" => $name]);
            }
        }

        return json_encode($varities);
    }

    public function getChildrensFromVarity(Request $request){
        $childs = [];
        $childrens = Varity::withTrashed()->where('father_id', $request->varity)->orWhere('mother_id', $request->varity)->get();

        foreach ($childrens as $key => $value) {
            $name = $value->name ? $value->name : $value->code;

            if(!empty($name)){
                array_push($childs, ["id" => $value->id,"name" => $name, "father_name" => @$value->fatherName(), "mother_name" => @$value->motherName()]);

            }
        }
        return json_encode($childs);
    }

    public function getBlocksByYear(Request $request){

        $blocks = Block::select('id', 'description')->where('year',$request->year)->get();

        #disernir los repetidos
        foreach ($blocks as $key => $value) {
            $count = Blocksvarity::where('block_id', $value->id)->where('varity_id','>',0)->count();
            $value->description .= "(".$count.")";
        }

        return json_encode($blocks);
    }

    public function getVarityByname(Request $request){

        $result = Varity::where('name','like',$request->year)->first();
        $varity = [];
        if(!empty($result)){
             $varity = [
                "IDVARITY" => $result->id,
                "NAME" => $result->name.';'.@$result->father->name.';'.$result->mother->name
             ];
        }

        return json_encode($varity);
    }

    public function getVarityBynameInfo(Request $request){

        $varity = Varity::where('name','like',$request->name)->first();
        $typesfeatures = Typefeature::where('status', 1)->get();
        foreach ($typesfeatures as $t_key => $t) {
            $m = $t->features()->where('status',1)->orderBy('id', 'asc')->get();

            foreach ($m as $key => $f) {
                $c = Characteristic::select('value')->where('varity_id',$varity->id)->where('feature_id',$f->id)->where('value','!=', '')->get();
                if(!empty($c) && $c->count() > 0){
                    if(is_numeric($c[0]->value)){

                        if($f->id == 16 || $f->id == 18){
                            #$typesfeatures[$t_key]->features[$key]->promedio = $c[0]->value;
                            foreach ($typesfeatures[$t_key]->features as $key_ => $val) {
                                if($val->id == $f->id){
                                    $typesfeatures[$t_key]->features[$key_]->promedio = $c[0]->value;
                                    #$typesfeatures[$t_key]->features[$key_]->std = $this->stats_standard_deviation($c);
                                }
                            }
                        }else{
                            foreach ($typesfeatures[$t_key]->features as $key_ => $val) {
                                if($val->id == $f->id){
                                    $typesfeatures[$t_key]->features[$key_]->promedio = (int)$c->avg('value');
                                    #$typesfeatures[$t_key]->features[$key_]->std = $this->stats_standard_deviation($c);
                                }
                            }
                        }

                    }else{
                        $typesfeatures[$t_key]->features[$key]->promedio = @$c->first()->value;
                        #$typesfeatures[$t_key]->features[$key]->std = "-";
                    }
                }
            }
        }

        $childrens_a = Varity::where('mother_id',$varity->id)->whereNotNull('name')->get();
        $childrens_b = Varity::where('father_id',$varity->id)->whereNotNull('name')->get();

        $childrens = $childrens_a->merge($childrens_b);

        $tree = Varity::whereIn('mother_id',[$varity->father_id, $varity->mother_id])->whereIn('father_id',[$varity->father_id, $varity->mother_id])->get();

        $count_childrens = count($childrens);
        $nietos = 0;
        if($count_childrens > 0){
            foreach ($childrens as $key => $value) {
                $count = Varity::where('mother_id',$value->id)->orWhere('father_id',$value->id)->whereNotNull('name')->count();
                $nietos += $count;
            }
        }

        $blocks = Blocksvarity::where('varity_id', $varity->id)->get();

        $ancesters = [];

        return View('results.varityResume',compact('varity','typesfeatures','tree', 'ancesters', 'childrens', 'blocks'));
    }

    function stats_standard_deviation_model( $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }

        $mean = $a->sum('value') / $n;

        $carry = 0.0;

        foreach ($a as $val) {
            $d = ((double) $val->value) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
           --$n;
        }
        return round(sqrt($carry / $n), 2);
    }

    public function getDataFeature(Request $request){
        $r = Characteristic::where('feature_id', $request->feature)->where('varity_id', $request->varity)->get();

        foreach ($r as $key => $value) {
            $response[$value['year']][] = $value;
        }

        return View('results.dataFeatures',compact('response'));
    }




}
